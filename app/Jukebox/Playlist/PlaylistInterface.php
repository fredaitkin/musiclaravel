<?php

namespace App\Jukebox\Playlist;

use Illuminate\Http\Request;

interface PlaylistInterface
{

    /**
     * Returns playlists
     */
    public function all(Request $request);

}
