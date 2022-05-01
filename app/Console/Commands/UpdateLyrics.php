<?php

namespace App\Console\Commands;

use App\Jukebox\Dictionary\WordCloudInterface as WordCloud;
use App\Jukebox\Song\SongInterface as Song;
use Exception;
use Illuminate\Console\Command;
use Log;

class UpdateLyrics extends Command {

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'db:lyrics
                            {--id= : Song id}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Runs song utilities';

    /**
     * Chart Lyrics API URL
     *
     * @var string
     */
    protected $url = "http://api.chartlyrics.com/apiv1.asmx/";

    /**
     * The song interface
     *
     * @var App\Jukebox\Song\SongInterface
     */
    private $song;

    /**
     * The wordCloud interface
     *
     * @var App\Jukebox\Dictionary\WordCloudInterface
     */
    private $wordCloud;
    /**
     * Constructor
     */
    public function __construct(Song $song, WordCloud $wordCloud)
    {
        parent::__construct();
        $this->song = $song;
        $this->wordCloud = $wordCloud;
        libxml_use_internal_errors(true);
    }
    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $options = $this->options();

        if(! empty($options['id'])):
            $this->updateLyrics($options['id']);
        else:
            $this->error('Song ID is required');
            exit;
        endif;

    }

    /**
     * Update song lyrics.
     *
     */
    protected function updateLyrics($id)
    {
        $artists_names = [
            'Dinosaur Junior'           => 'Dinosaur Jr.',
            'Florence + the Machine'    => 'Florence and The Machine',
            'Run-D.M.C'                 => 'Run-D.M.C.',
            'Pink'                      => 'P!nk',
        ];
        $song = $this->song->retrieveSongs(['id' => $id]);

        if (isset($song[0]->id)):

            if (isset($artists_names[$song[0]->artist])):
                $song[0]->artist = $artists_names[$song[0]->artist];
            endif;

            try {
                $artist = $song[0]->artist;
                if ($artist == 'Compilations'):
                    $artist = trim($song[0]->notes);
                endif;
                $this->info("ARTIST: " . $artist);
                $this->info("TITLE: " . $song[0]->title);
                $this->info("ID: " . $song[0]->id);
                $lyric = $this->directSearch($artist, $song[0]->title);
                if (! $lyric):
                    $lyric = $this->search($artist, $song[0]->title);
                endif;

                if (! empty($lyric)):
                    $this->info($lyric);
                    $this->info("");
                    $this->info($artist . ' : ' . $song[0]->title);
                    $save = $this->ask('Save the lyrics?');
                     if ($save === 'y'):
                        $lyric = $this->wordCloud->process($lyric, 'add', $song[0]->id);
                        $song[0]->lyrics = $lyric;
                        $song[0]->save();
                    else:
                        $this->info("Ignoring");
                    endif;
                endif;

            } catch (Exception $e) {

                Log::info($song[0]->title . ' ' . $artist);
                Log::info($e->getMessage());
            }

             $this->info("ID: " . $song[0]->id);

        endif;

    }

    private function executeCurlRequest($url) {
        $curl = curl_init();

        curl_setopt_array($curl, [
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_HTTPHEADER => array("Content-type: text/xml"),
        ]);

        $response = curl_exec($curl);
        $err = curl_error($curl);

        if ($err):
            throw new Exception($err);
        endif;

        return $response;
    }

    /**
     * Perform direct search via API.
     *
     * @param string $artist Song artist
     * @param string $song Song title
     */
    private function directSearch($artist, $song) {
        $this->info("Direct Search");

        $response = $this->executeCurlRequest($this->url . "SearchLyricDirect?artist=" . urlencode($artist) . "&song=" . urlencode($song));
        $this->info($this->url . "SearchLyricDirect?artist=" . urlencode($artist) . "&song=" . urlencode($song));
        $xml = simplexml_load_string($response);

        if (false === $xml) {
            return false;
        }

        if (isset($xml) && ! empty($xml->Lyric)):
            $this->info("LYRIC ARTIST: " . $xml->LyricArtist);
            $this->info("LYRIC SONG: " . $xml->LyricSong);
            if (strcasecmp($artist, (string) $xml->LyricArtist) === 0 && strcasecmp($song, (string) $xml->LyricSong) === 0):
                return (string) $xml->Lyric;
            endif;
        endif;

        return false;
    }

    /**
     * Perform broader search via API.
     *
     * @param string $artist Song artist
     * @param string $song Song title
     */
    private function search($artist, $song) {
        $this->info("Wide Search");
        $response = $this->executeCurlRequest($this->url . "SearchLyric?artist=" . urlencode($artist) . "&song=" . urlencode($song));
        $xml = simplexml_load_string($response);

        if (false === $xml) {
            return false;
        }

        if (isset($xml->SearchLyricResult) && ! empty($xml->SearchLyricResult)):
            foreach ($xml->SearchLyricResult as $result):
                $this->info("Result Artist: " . $result->Artist);
                $this->info("Result Song: " . $result->Song);
                if (strcasecmp($artist, $result->Artist) === 0):
                    if (strcasecmp($song, $result->Song) === 0):
                        $this->info("Getting lyrics");
                        $this->info("Lyric ID: " . $result->LyricId);
                        $this->info("LyricChecksum: " . $result->LyricChecksum);
                        if ($result->LyricId > 0 && ! empty($result->LyricChecksum)):
                            return $this->getLyric($result->LyricId, $result->LyricChecksum);
                        endif;
                    endif;
                endif;
            endforeach;
        endif;

        return false;
    }

    /**
     * Retrieve lyric by id via API.
     *
     * @param string $id Chart Lyric Id
     * @param string $checksum Checksum
     */
    private function getLyric($id, $checksum) {
        $response = $this->executeCurlRequest($this->url . "GetLyric?lyricId=" . $id . "&lyricCheckSum=" . $checksum);
        if (strpos($response, '<') === 0):
            $xml = simplexml_load_string($response);
            return (string) $xml->Lyric;
        else:
            $this->info($response);
        endif;

        return false;
    }

}
