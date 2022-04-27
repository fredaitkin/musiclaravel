<?php

namespace App\Jukebox\Playlist;

use Illuminate\Http\Request;

interface PlaylistInterface
{

    /**
     * Returns playlists
     *
     * @return LengthAwarePaginator Paginated list of artists.
     */
    public function all(Request $request);

}