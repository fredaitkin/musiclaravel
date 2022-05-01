<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class QueryRestController extends Controller
{

    /**
     * Display page
     *
     * @param  Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        return view('query', ['show_cols' => 1]);
    }

}
