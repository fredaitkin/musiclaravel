<?php

namespace App\Http\Controllers;

use App\Jukebox\Artist\ArtistInterface as Artist;
use App\Jukebox\Song\SongInterface as Song;
use App\Traits\StoreImageTrait;
use DB;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;

class ArtistResourceController extends Controller
{

    use StoreImageTrait;

    /**
     * The artist interface
     *
     * @var App\Jukebox\Artist\SongInterface
     */
    private $artist;

    /**
     * The song interface
     *
     * @var App\Jukebox\Song\SongInterface
     */
    private $song;

    /**
     * Constructor
     */
    public function __construct(Artist $artist, Song $song)
    {
        $this->artist = $artist;
        $this->song = $song;
    }

    /**
     * Search for artist.
     *
     * @param  Request request
     * @return Response
     */
    public function search(Request $request)
    {
        $q = $request->q;
        if ($q != "") {
            $data = $this->retrieveArtists($q);
        } else {
            $data = $this->retrieveArtists(session()->get('artists_query'));
        }

        // Data object can be a view or a paginator
        if (get_class($data) === 'Illuminate\View\View') {
            return $data;
        } else {
            if ($data->total() > 0) {
                return view('artists', ['q' => $q, 'artists' => $data]);
            } else {
                return view('artists', ['q' => $q, 'artists' => $data])->withMessage('No artists found. Try to search again!');
            }
        }
    }

    /**
     * Retrieve artists
     *
     * @param  string $query
     * @return array
     */
    protected function retrieveArtists($query) {
        if ($query != "") {
            session()->put('artists_query', $query);
            if ( stripos( $query, 'SELECT') === 0 ) {
                return $this->adminSearch($query);
            } else {
                return $this->artist->search($query);
            }
        } else {
            return Artist::orderBy('artist')->paginate();
        }
    }

     /**
     * Perform admin search on artists
     *
     * @param  string $query
     * @return Response
     */
    public function adminSearch(string $query)
    {
        if (! isValidReadQuery($query)) {
            abort(403, 'Unauthorized action.');
        }

        try {
            $artists = DB::select($query);

        } catch (\Illuminate\Database\QueryException $ex) {
            $artists = [];
        }
        $paginate = new LengthAwarePaginator($artists, count($artists), 10, 1, [
            'path' =>  request()->url(),
            'query' => request()->query(),
        ]);

        if (count($artists) > 0) {
            return view('artists', ['q' => $query, 'artists' => $paginate]);
        } else {
            return view('artists', ['q'  => $query, 'artists' => $paginate])->withMessage('No artists found. Try to search again!');
        }
    }

    /**
     * Return artists ajax
     *
     * @return Response
     */
    public function artist_ajax(Request $request)
    {
        $data = [];

        if ($request->has('q')) {
            $data = Artist::searchByName($request->q);
        }

        return response()->json($data);
    }

    /**
     * Return artists ajax
     *
     * @return Response
     */
    public function songs($id) {
        // @todo Get songs when artist is compilation.
        return response()->json($this->artist->get($id)->songs);
    }
}
