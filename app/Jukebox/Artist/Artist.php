<?php

namespace App\Jukebox\Artist;

use Illuminate\Http\Request;

class Artist implements ArtistInterface
{

    /**
     * Returns artists
     *
     * @return LengthAwarePaginator Paginated list of artists.
     */
    public function all()
    {
        return ArtistModel::orderBy('artist')->paginate();
    }

    /**
     * Retrieve an artist.
     *
     * @param int $id
     */
    public function get($id)
    {
        return ArtistModel::find($id);
    }

    /**
     * Returns all artists
     *
     * @param array $fields Specific fields to retrieve.
     * @return Collection Eloquent collection of artists.
     */
    public function getAllArtists(array $fields = null)
    {
        if ($fields) {
            return ArtistModel::all($fields);
        } else {
            return ArtistModel::all();
        }
    }

    /**
     * Create or update an artist.
     *
     * @param Request $request
     */
    public function createOrUpdate(Request $request)
    {
        $validator = $request->validate([
            'artist' => 'required|max:255',
        ]);

        if (isset($request->id)):
            $model = ArtistModel::find($request->id);
        else:
            $model = new ArtistModel();
        endif;

        $model->artist = $request->artist;
        $model->is_group = isset($request->is_group);
        $model->location = $request->location;
        $model->country = $request->country;
        $model->group_members = $request->group_members;
        $model->notes = $request->notes;
        
        $model->save();
    }

    /**
    * Create an artist via the music loading process.
    *
    * @param array $artist
    * @return integer
    */
    public function dynamicStore(array $artist)
    {
        return ArtistModel::insertGetId([
            'artist'    => $artist[0],
            'is_group'  => $artist[1],
            'country'   => $artist[2],
        ]);
    }

    /**
     * Does the artist exist
     *
     * @param  string $artist_name Artist name
     * @return boolean
     */
    public function getID($artist_name)
    {
        $artist = ArtistModel::where("artist", $artist_name)->first();
        if ($artist) {
            return $artist->toArray()['id'];
        }
        return false;
    }

    /**
     * Is the "artist" a Compilation?
     *
     * @param integer $id Artist id
     * @return boolean
     */
    public function isCompilation($id)
    {
        $artist = ArtistModel::where(["id" => $id])->get(['artist'])->first()->toArray();
        return $artist['artist'] === 'Compilations';
    }

    /**
     * Remove the artist and all their songs from the database
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id)
    {
        ArtistModel::findOrFail($id)->delete();
    }

    /**
    * Search for artists
    *
    * @param string $query
    */
    public function search($query) {
        return ArtistModel::select('artists.*')
            ->where('artist', 'LIKE', '%' . $query . '%')
            ->orWhere('country', 'LIKE', '%' . $query . '%')
            ->paginate()
            ->appends(['q' => $query])
            ->setPath('');
    }

    /**
    * Search for artists by name
    *
    * @param string $search
    */
    public function searchByName($search) {
        return ArtistModel::select('id', 'artist as text')
            ->where('artist', 'LIKE', '%' . $search . '%')
            ->get();
    }

}
