<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Jukebox\Song\Song;
use Illuminate\Http\Request;

class ManagedQueryRestController extends Controller
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

}
