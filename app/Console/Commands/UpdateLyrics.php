<?php

namespace App\Console\Commands;

use App\Music\Song\Song;
use App\Words\WordCloud;
use DB;
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
     * Create a new command instance.
     *
     */
    public function __construct()
    {
        parent::__construct();
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
        $song = DB::select("select s.id, title, artist, s.notes from songs s left join artist_song ass on ass.song_id = s.id left join artists a on ass.artist_id = a.id where s.id > ? and lyrics is NULL LIMIT 1", [$id]);

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
                        $_song = Song::find($song[0]->id);
                        $word_cloud = new WordCloud();
                        $word_cloud->process($lyric, 'add', $song[0]->id);
                        $_song->lyrics = $lyric;
                        $_song->save();
                        $this->info("SAVED");
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
