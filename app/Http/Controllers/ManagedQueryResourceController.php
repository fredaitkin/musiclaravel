<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Jukebox\Song\SongInterface as Song;
use Illuminate\Http\Request;

class ManagedQueryResourceController extends Controller
{

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
        $this->song = $song;
    }

    /**
     * Run query
     *
     * @param  Request $request
     * @return Response
     */
    public function query(Request $request)
    {
        $queries = [
            '' => 'Please Select',
            '1' => 'Words in Songs',
        ];
        if (isset($request->predefined_query)):
            switch($request->predefined_query):
                case 1:
                    $results = $this->wordsInSongs($request->params);
                    break;
            endswitch;
        endif;
        return view('managed_query', [
            'predefined_query' => $request->predefined_query,
            'params' => $request->params,
            'queries' => $queries,
            'results' => $results ?? "Nothing returned",
        ]);
    }

    private function wordsInSongs($words)
    {
        $results = [];
        $songs = $this->song->getSongsByLyric($words);
        $results['headings'] = ['ID', 'TITLE', 'PHRASE'];
        $results['rows'] = [];
        foreach($songs as $song):
            $pos = stripos($song->lyrics, $words);
            if ($pos > 50):
                $pos -= 50;
            endif;
            $phrase = substr($song->lyrics, $pos, 150);
            $phrase = str_replace(["\r\n", "\n", "\\"], [' ', ' ', ''], $phrase);
            if (stripos($phrase, $words) !== 0) {
                // Strip start to get whole word
                $start_pos = strpos($phrase, " ");
                $phrase = substr($phrase, $start_pos);
            }
            // Strip end to get whole word.
            $end_pos = strrpos($phrase, " ");
            $phrase = substr($phrase, 0, $end_pos);
            $phrase = str_replace($words, "<strong class='text-success'>" . $words . "</strong>", $phrase);
            $results['rows'][] = [$song->id, strlen($song->title) < 25 ? $song->title : str_pad(substr($song->title, 0, 25), 30, "."), trim($phrase)];
        endforeach;
        return $results;
    }

}