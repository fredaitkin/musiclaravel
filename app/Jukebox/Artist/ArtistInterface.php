<?php

namespace App\Jukebox\Artist;

use Illuminate\Http\Request;

interface ArtistInterface
{

    /**
     * Returns artists
     *
     * @return LengthAwarePaginator Paginated list of artists.
     */
    public function all();

    /**
     * Returns all artists
     *
     * @param array $fields Specific fields to retrieve.
     * @return Collection Eloquent collection of artists.
     */
    public function getAllArtists(array $fields = null);

    /**
     * Create or update an artist.
     *
     * @param Request $request
     */
    public function createOrUpdate(Request $request);

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
     * Remove the artist and all their songs from the database
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id);

    /**
    * Search for artists
    *
    * @param string $query
    */
    public function search($query);

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
