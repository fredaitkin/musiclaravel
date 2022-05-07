<?php

namespace App\Jukebox\Artist;

use Illuminate\Http\Request;

interface ArtistInterface
{

    /**
     * Retrieve an artist.
     *
     * @param  int $id
     * @return \App\Jukebox\Artist\ArtistModel
     */
    public function get($id);

    /**
     * Returns artists
     *
     * @param  Request $request
     *
     * @return LengthAwarePaginator Paginated list of artists.
     */
    public function all(Request $request);

    /**
     * Create or update an artist.
     *
     * @param Request  $request
     */
    public function createOrUpdate(Request $request);

    /**
    * Search for artists
    *
    * @param string $query
    */
    public function search($query);

    /**
    * Create an artist via the music loading process.
    *
    * @param array $artist
    * @return integer
    */
    public function dynamicStore(array $artist);

    /**
     * Does the artist exist
     *
     * @param  string $artist_name Artist name
     * @return boolean
     */
    public function getID($artist_name);

    /**
     * Is the "artist" a Compilation?
     *
     * @param integer $id Artist id
     * @return boolean
     */
    public function isCompilation($id);

    /**
    * Search for artists by name
    *
    * @param string $search
    */
    public function searchByName($search);

    /**
    * Search for artists by name
    *
    * @param string $search
    */
    public function getArtistAlbums($id);

}
