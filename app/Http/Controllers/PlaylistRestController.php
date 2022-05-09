<?php

namespace App\Http\Controllers;

use App\Jukebox\Playlist\PlaylistInterface as Playlist;
use Illuminate\Http\Request;

class PlaylistRestController extends Controller
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
     * Display playlists
     * @return mixed
     */
    public function index(Request $request)
    {
        $playlists = $this->playlist->all($request);
        if (empty($request->all()) || $request->has('page')):
            return view('playlists', ['playlists' => $playlists]);
        endif;
        return $playlists;
    }

    /**
     * Add songs to a playlist
     *
     * @param Request $request
     * @return Response
     */
    public function store(Request $request)
    {
        return $this->playlist->createOrUpdate($request);
    }

    /**
     * Remove the playlist
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy(Request $request, $playlist)
    {
        $this->playlist->delete($playlist);
        return view('playlists', ['playlists' => $this->playlist->all($request)]);
    }

}
