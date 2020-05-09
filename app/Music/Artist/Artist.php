<?php

namespace MySounds\Music\Artist;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class Artist extends Model
{

    protected $table = 'artists';


	/**
     * The primary key for the model.
     *
     * @var integer
     */
    protected $id;

    /**
     * The artist.
     *
     * @var string
     */
    protected $artist;

    /**
     * Is the artist a group.
     *
     * @var bool
     */
    protected $is_group;

    /**
     * The country the artist is from.
     *
     * @var string
     */
    protected $country;

    /**
     * Created date.
     *
     * @var datetime
     */
    protected $created_at;

    /**
     * Updated date.
     *
     * @var datetime
     */
    protected $updated_at;

    /**
     * Members of the band.
     *
     * @var string
     */
    protected $group_members;

    /**
     * Notes about the artist.
     *
     * @var string
     */
    protected $notes;

	/**
	 * The attributes that aren't mass assignable.
	 *
	 * @var array
	 */
	protected $guarded = [];

    /**
     * The number of records to return for pagination.
     *
     * @var int
     */
    protected $perPage = 10;

    /**
     * Encode the artist's name.
     *
     * @param string $value
     * @return string
     */
    public function getArtistAttribute($value)
    {
        return utf8_encode($value);
    }

	/**
     * Returns artists
     *
     * @return LengthAwarePaginator Paginated list of artists.
     */
    public static function get_artists()
    {
        return Artist::orderBy('artist')->paginate();
    }

	/**
     * Returns all artists
     *
     * @param array $fields Specific fields to retrieve.
     * @return Collection Eloquent collection of artists.
     */
    public static function get_all_artists(array $fields = null)
	{
		if ($fields) {
			return Artist::all($fields);
		} else {
			return Artist::all();
		}
	}

	/**
     * Create or update an artist.
     *
	 * @param Request $request
     */
    public static function store(Request $request)
    {
        $validator = $request->validate([
            'artist' => 'required|max:255',
        ]);

        $artist = [];
        $artist['artist'] = $request->artist;
        $artist['is_group'] = isset($request->is_group);
        $artist['country'] = $request->country;
        $artist['group_members'] = $request->group_members;
        $artist['notes'] = $request->notes;

		if (isset($request->id)) {
			// updateOrCreate throwing duplicate error
			Artist::where('id', $request->id)->update($artist);
        } else {
			Artist::create($artist);
        }
    }

	/**
	* Create an artist via the music loading process.
	*
	* @param array $artist
	* @return integer
	*/
	public static function dynamic_store(array $artist)
	{
		return Artist::insertGetId([
			'artist' 	=> $artist[0],
			'is_group' 	=> $artist[1],
			'country' 	=> $artist[2],
		]);
	}

    /**
     * Does the artist exist
     *
     * @param  string $artist_name Artist name
     * @return boolean
     */
    public static function get_id($artist_name)
    {
        $artist = Artist::where("artist", $artist_name)->first();
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
    public static function is_compilation($id)
    {
        $artist = Artist::where(["id" => $id])->get(['artist'])->first()->toArray();
        return $artist['artist'] === 'Compilations';
    }

    /**
     * Remove the artist and all their songs from the database
     *
     * @param  int  $id
     * @return Response
     */
    public static function destroy($id)
    {
        Artist::findOrFail($id)->delete();
    }

}
