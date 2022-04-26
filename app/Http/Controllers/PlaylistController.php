<?php

namespace App\Http\Controllers;

use App\Music\Playlist\Playlist;
use App\Music\Song\Song;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Log;
class PlaylistController extends Controller
{

    /**
     * Display playlists
     * @param Request $request
     * @return mixed
     */
    public function index(Request $request)
    {
        if (empty($request->all())):
            $playlists = Playlist::get(['name']);
            return view('playlists', ['playlists' => $playlists ?? []]);
        endif;

        if (isset($request->all)):
            $query = Playlist::select('name');
            if (isset($request->notIn)):
                $query->where('playlist', 'not like', '%"id": "' . intval($request->notIn) . '"%');
            endif;
            return $query->get();
        endif;
    }

    /**
     * Remove the playlist
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($playlist)
    {
        Playlist::where(['name' => $playlist])->delete();
        return redirect('/playlists');
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

        $playlist = Playlist::where(['name' => $request->playlist])->get(['playlist'])->toArray();
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
