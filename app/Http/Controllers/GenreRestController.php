<?php

namespace App\Http\Controllers;

use App\Jukebox\Song\SongInterface as Song;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class GenreRestController extends Controller
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
     * Display genres
     *
     * @return Response
     */
    public function index()
    {
        return view('genres', ['genres' => $this->song->getGenres()]);
    }

}
