<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class QueryController extends Controller
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
        return view('query', ['queries' => $queries, 'show_cols' => 1]);
    }

}
