<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Music\Song\Song;
use Illuminate\Http\Request;

class ManagedQueryController extends Controller
{

    /**
     * Display page
     *
     * @param  Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        $queries = [
            '' => 'Please Select',
            '1' => 'Words in Songs',
        ];
        return view('managed_query', ['queries' => $queries]);
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
        $songs = Song::select('id', 'title', 'lyrics')->where('lyrics', 'like', '%' . $words . '%')->get();
        $results[] = ['SONG ID', 'TITLE', 'PHRASE'];
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
            $results[] = [$song->id, substr($song->title, 0, 20), trim($phrase)];
        endforeach;
        return $results;
    }

}
