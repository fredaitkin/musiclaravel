<?php

namespace App\Http\Controllers;

use App\Jukebox\Playlist\PlaylistInterface as Playlist;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PlaylistResourceController extends Controller
{

    /**
     * The playlist interface
     *
     * @var App\Jukebox\Playlist\PlaylistInterface
     */
    private $playlist;

    /**
     * Constructor
     */
    public function __construct(Playlist $playlist)
    {
        $this->playlist = $playlist;
    }

    /**
     * Retrieve playlists
     *
     * @param Request $request
     * @return Response
     */
    public function playlists(Request $request)
    {
        $playlists = Playlist::get(['name']);
        return ['playlists' => $playlists, 'status_code' => 200];
    }

    /**
     * Retrieve songs in a playlist
     *
     * @param Request $request
     * @return Response
     */
    public function songs(Request $request)
    {
        $playlist = $this->playlist->get($request->playlist);
        return ['songs' => json_decode($playlist[0]['playlist']), 'status_code' => 200];
    }

}
