<?php

namespace App\Jukebox\Song;

use Illuminate\Http\Request;

interface SongInterface
{

    /**
     * Create a song.
     *
     * @param Request $request
     */
    public function createOrUpdate(Request $request);

    /**
     * Retrieve a song.
     *
     * @param int $id
     */
    public function get($id);

    /**
     * Retrieve all songs;
     */
    public function all();

    /**
    * Remove the song
    *
    * @param  int  $id
    */
    public function delete($id);

    /**
     * Update song lyrics.
     *
     * @param Request $request
     */
    // public function updateLyrics($request);

    /**
    * Get song genres
    */
    public function getGenres();

    /**
     * Does the album exist
     *
     * @param integer $id Artist id
     * @param string $album_name Album name
     * @return boolean
     */
    public function doesAlbumExist($id, $album_name);

    /**
     * Does the song exist
     *
     * @param integer $id Artist id
     * @param string $title Song title
     * @return boolean
     */
    public function doesSongExist($id, $title);

    /**
    * Returns all song titles
    *
    * @param string $album Restrict via album.
    * @return Collection Eloquent collection of song titles.
    */
    public function getSongTitles(string $album = null);

    public function isSong($file);

    /**
    * Retrieve artist's songs.
    *
    * Retrieves the songs from the artist's albums and compilation albums.
    *
    * @param int $id
    * @param string $artist
    */
    public function getArtistSongs($id, $artist);

    /**
    * Retrieve album songs by song id.
    *
    * @param int $id
    */
    public function getAlbumSongsBySongID($id);

    /**
    * Search for songs
    *
    * @param string $query
    */
    public function search($query);

    public function songs(Request $request);

}
