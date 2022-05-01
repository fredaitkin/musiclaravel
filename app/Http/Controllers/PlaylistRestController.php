<?php

namespace App\Http\Controllers;

use App\Jukebox\Playlist\PlaylistInterface as Playlist;
use App\Jukebox\Song\SongInterface as Song;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Log;
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
        if (empty($request->all())):
            $playlists = $this->playlist->all($request);
            return view('playlists', ['playlists' => $playlists]);
        endif;

        return $this->playlist->playlists($request);
    }

    /**
     * Add songs to a playlist
     *
     * @param Request $request
     * @return Response
     */
    public function store(Request $request)
    {
        $this->playlist->createOrUpdate($request);
        return ['status_code' => 200];
    }

    /**
     * Remove the playlist
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy(Request $request, $playlist)
    {
        $this->playlist->destroy($playlist);
        return view('playlists', ['playlists' => $this->playlist->all($request)]);
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

}