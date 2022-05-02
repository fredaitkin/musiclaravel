<?php

namespace App\Jukebox\Playlist;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class Playlist implements PlaylistInterface
{

    /**
     * Returns playlists
     *
     * @return LengthAwarePaginator Paginated list of artists.
     */
    public function all(Request $request)
    {
        return PlaylistModel::get(['name']);
    }

    /**
     * Retrieve an artist.
     *
     * @param int $id
     */
    public function get($name)
    {
        return PlaylistModel::where(['name' => $name])->get(['playlist'])->toArray();
    }

    /**
     * Create or update a playlist.
     *
     * @param Request $request
     */
    public function createOrUpdate(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id'        => 'required|numeric',
            'playlist'  => 'required|max:10',
        ]);

        // Validate parameters
        if ($validator->fails()):
            return ['errors' => $validator->errors()->all(), 'status_code' => 422];
        endif;

        $playlist = PlaylistModel::where(['name' => $request->playlist])->first();
        if (empty($playlist)):
            $playlist = new PlaylistModel();
        endif;
        $playlist->name = $request->playlist;

        if(isset($playlist->playlist)) {
            $existing_playlist = (array) json_decode($playlist->playlist);
        } else {
            $playlist->playlist = [];
        }
        $existing_playlist[] = ['id' => $request->id, 'title' => $request->title];
        $playlist->playlist = json_encode($existing_playlist);
        $playlist->save();

        return ['status_code' => 200];
    }

    /**
     * Returns playlists
     *
     * @return LengthAwarePaginator Paginated list of artists.
     */
    public function playlists(Request $request)
    {
        if (isset($request->all)):
            $query = PlaylistModel::select('name');
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
    public function destroy($name)
    {
        PlaylistModel::where(['name' => $name])->delete();
    }

}
