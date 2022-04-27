<?php

namespace App\Http\Controllers;

use App\Jukebox\Playlist\PlaylistInterface as Playlist;
use App\Jukebox\Song\SongInterface as Song;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Log;
class PlaylistResourceController extends Controller
{

    /**
     * The playlist interface
     *
     * @var App\Jukebox\Playlist\PlaylistInterface
     */
    private $playlist;

    /**
     * The song interface
     *
     * @var App\Jukebox\Song\SongInterface
     */
    private $song;

    /**
     * Constructor
     */
    public function __construct(Playlist $playlist, Song $song)
    {
        $this->playlist = $playlist;
        $this->song = $song;
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
        $validator = Validator::make($request->all(), [
            'playlist' => 'required|max:100',
        ]);

        // Validate parameters
        if ($validator->fails()):
            return ['errors' => $validator->errors(), 'status_code' => 422];
        endif;

        $playlist = $this->playlist->get($request->playlist);
        return ['songs' => json_decode($playlist[0]['playlist']), 'status_code' => 200];
    }


    /**
     * Add songs to a playlist
     *
     * @param Request $request
     * @return Response
     */
    public function save(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id'        => 'required|numeric',
            'playlist'  => 'required|max:100',
        ]);

        // Validate parameters
        if ($validator->fails()):
            return ['errors' => $validator->errors(), 'status_code' => 422];
        endif;

        $playlist = Playlist::firstOrNew(array('name' => $request->playlist));
        $playlist->name = $request->playlist;

        $song = Song::find($request->id);Log::info($song);
        if(isset($playlist->playlist)) {
            $existing_playlist = (array) json_decode($playlist->playlist);
        } else {
            $playlist->playlist = [];
        }
        $existing_playlist[] = ['id' => $request->id, 'title' => $song['title']];
        $playlist->playlist = json_encode($existing_playlist);
        $playlist->save();

        return ['status_code' => 200];
    }
}
