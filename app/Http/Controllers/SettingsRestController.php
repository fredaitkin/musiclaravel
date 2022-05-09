<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;

class SettingsRestController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        return view('settings', ['media_directory' => config('filesystems.media_directory')]);
    }

}
