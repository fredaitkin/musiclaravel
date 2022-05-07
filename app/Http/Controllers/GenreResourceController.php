<?php

namespace App\Http\Controllers;

use App\Jukebox\Song\SongInterface as Song;
use Illuminate\Http\Request;

class GenreResourceController extends Controller
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
     * Retrieve songs in a genre
     *
     * @param Request $request
     * @return Response
     */
    public function songs(Request $request)
    {
        $songs = $this->song->all($request);
        return ['songs' => $songs, 'status_code' => 200];
    }

}
