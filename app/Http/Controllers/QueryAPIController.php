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
        $query = $request->myquery;
        $results = '';
        $count = 0;
        $show_cols = isset($request->show_cols);
        if (!empty($query)):
            try {
                if (stripos($query, 'delete') !== false):
                    throw new Exception('This operation is not allowed');
                endif;
                $rows = DB::select($query);
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
            'query' => $query,
            'results' => $results,
            'show_cols' => $show_cols,
        ]);
    }

}
