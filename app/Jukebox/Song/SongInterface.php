<?php

namespace App\Jukebox\Song;

use Illuminate\Http\Request;

interface SongInterface
{

    /**
     * Create a song.
     *
     * @param  Request  $request
     * @return void
     */
    public function createOrUpdate(Request $request);

    /**
     * Retrieve a song.
     *
     * @param  int $id
     * @return \App\Jukebox\Song\SongModel
     */
    public function get($id);

    /**
     * Retrieve all songs;
     *
     * @return array
     */
    public function all();

    /**
    * Remove the song
    *
    * @param  int  $id
    * @return void
    */
    public function delete($id);

    /**
    * Get song genres
    *
    * @return array 
    */
    public function getGenres();

    /**
     * Does the album exist
     *
     * @param  int  $id
     * @param  string  $album_name
     * @return bool
     */
    public function doesAlbumExist($id, $album_name);

    /**
     * Does the song exist
     *
     * @param  int  $id
     * @param  string $title
     * @return bool
     */
    public function doesSongExist($id, $title);

    /**
    * Returns all song titles
    *
    * @param  string  $album
    * @return \Illuminate\Database\Eloquent\Collection|static[]
    */
    public function getSongTitles(string $album = null);

    /**
    * Is the file a song.
    *
    * @param  string  $file
    * @return bool
    */
    public function isSong($file);

    /**
    * Retrieve artist's songs.
    *
    * Retrieves the songs from the artist's albums and compilation albums.
    *
    * @param  int  $id
    * @param  string  $artist
    * @return \Illuminate\Database\Eloquent\Collection|static[]
    */
    public function getArtistSongs($id, $artist);

    /**
    * Retrieve album songs by song id.
    *
    * @param  int  $id
    * @return \Illuminate\Database\Eloquent\Collection|static[]
    */
    public function getAlbumSongsBySongID($id);

    /**
    * Search for songs
    *
    * @param string $query
    * @return \Illuminate\Database\Eloquent\Collection|static[]
    */
    public function search($query);

    /**
    * Retrieve songs
    *
    * @param  Request $request
    * @return \Illuminate\Database\Eloquent\Collection|static[]
    */
    public function songs(Request $request);

}
