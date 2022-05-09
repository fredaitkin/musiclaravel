<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;

class UtilitiesRestController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        return view('utilities', ['media_directory' => config('filesystems.media_directory')]);
    }

}
