<?php

namespace App\Console\Commands;

use App\Jukebox\Song\SongInterface as Song;
use Exception;
use Illuminate\Console\Command;
use Log;

class UpdateSongs extends Command {

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'db:songs
                            {--lyrics : Update lyrics}
                            {--art : Update cover art}
                            {--ids= : Comma separated list of ids}';

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
     * Constructor
     */
    public function __construct(Song $song)
    {
        parent::__construct();
        $this->song = $song;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $options = $this->options();

        $ids = null;
        if(! empty($options['ids'])):
            $ids = explode(',', $options['ids']);
        endif;

        if(! empty($options['lyrics'])):
            $this->updateLyrics($ids);
        endif;

        if(! empty($options['art'])):
            $this->updateCoverArt($ids);
        endif;

    }

    /**
     * Update song lyrics.
     *
     */
    protected function updateLyrics($ids)
    {

        $songs = $this->song->retrieveSongs(['ids' => $ids]);

        foreach ($songs as $song):

            try {
                $artist = $song->artist;
                if ($artist == 'Compilations'):
                    $artist = trim($song->notes);
                endif;
                $lyric = $this->directSearch($artist, $song->title);
                if (empty($lyric)):
                    $lyric = $this->search($artist, $song->title);
                endif;

                if (! empty($lyric)):
                    $song->lyrics = $lyric['lyric'];
                    $song->cover_art = serialize(['api' => $lyric['cover_art']]);
                    $song->save();
                else:
                    throw new Exception('Not found');
                endif;

            } catch (Exception $e) {
                Log::info($song->title . ' ' . $artist);
                Log::info($e->getMessage());
            }

        endforeach;

    }

    /**
     * Update cover art lyrics.
     *
     */
    protected function updateCoverArt($ids)
    {

        $songs = $this->song->retrieveSongs(['ids' => $ids, 'cover_art_empty' => true]);

        foreach ($songs as $song):

            try {
                $artist = $song->artist;
                if ($artist == 'Compilations'):
                    $artist = trim($song->notes);
                endif;
                $lyric = $this->directSearch($artist, $song->title);
                if (! empty($lyric)):
                    $song->cover_art = serialize(['api' => $lyric['cover_art']]);
                    $song->save();
                else:
                    throw new Exception('Not found');
                endif;

            } catch (Exception $e) {
                Log::info($song->title . ' ' . $artist);
                Log::info($e->getMessage());
            }

        endforeach;

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
