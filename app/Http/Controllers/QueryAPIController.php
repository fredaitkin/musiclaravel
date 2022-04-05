<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Music\Song\Song;
use DB;
use Exception;
use Illuminate\Http\Request;

class QueryAPIController extends Controller
{

    /**
     * Run query
     *
     * @param  Request $request
     * @return Response
     */
    public function query(Request $request)
    {
        $myquery = '';
        $predefined_query = '';
        $results = '';
        $count = 0;
        $show_cols = isset($request->show_cols);
        $queries = [
            '' => 'Please Select',
            '1' => 'Words in Songs',
        ];
        if (isset($request->predefined_query)):
            $predefined_query = $request->predefined_query;
            $myquery = $request->myquery;
            switch($predefined_query):
                case 1:
                    $results = $this->wordsInSongs($request->myquery);
                    break;
            endswitch;
        elseif (isset($request->myquery)):
            $myquery = $request->myquery;
            try {
                if (stripos($request->myquery, 'delete') !== false):
                    throw new Exception('This operation is not allowed');
                endif;
                $rows = DB::select($request->myquery);
                foreach($rows as $row):
                    $row = (array) $row;
                    foreach($row as $col => $val):
                        if ($show_cols):
                            $results .= $col . ' ';
                        endif;
                        $results .= $val . ' ';
                    endforeach;
                    $results .= "\n";
                    $count++;
                endforeach;
                $results = 'Count: ' . $count . "\n\n" . $results;
            } catch (Exception $e) {
                $results = $e->getMessage() . "\n";
            }
        endif;
        return view('query', [
            'myquery' => $myquery,
            'predefined_query' => $predefined_query,
            'queries' => $queries,
            'results' => $results,
            'show_cols' => $show_cols,
        ]);
    }

    private function wordsInSongs($words)
    {
        $results = '';
        $songs = Song::select('id', 'title', 'lyrics')->where('lyrics', 'like', '%' . $words . '%')->get();
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
            // @todo Improve presentation.
            $results .= str_pad($song->id, 6) . ' ' . str_pad(substr($song->title, 0, 20), 30, ".") . ' ' . trim($phrase) . "\n";
        endforeach;
        return 'Count: ' . count($songs) . "\n\n" . $results;
    }

}
