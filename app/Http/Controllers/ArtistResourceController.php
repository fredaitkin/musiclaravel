<?php

namespace App\Http\Controllers;

use App\Jukebox\Artist\ArtistInterface as Artist;
use App\Jukebox\Song\SongInterface as Song;
use Illuminate\Http\Request;

class ArtistResourceController extends Controller
{

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
     * Show the form for creating a new artist
     *
     * @return Response
     */
    public function add()
    {
        return view('artist', [
            'title'     => 'Add New Artist',
            'countries' => array_merge(['' => 'Please Select...'], config('countries')),
        ]);
    }

    /**
     * Search for artist.
     *
     * Using session object retains the list of artists after Back has been
     * selected on Artist page.
     *
     * @param  Request request
     * @return Response
     */
    public function search(Request $request)
    {
        $q = $request->q;
        if ($q != ""):
            session()->put('artists_query', $q);
        endif;

        $data = $this->artist->search(session()->get('artists_query'));

        // Data object can be a view or a paginator
        if (get_class($data) === 'Illuminate\View\View'):
            return $data;
        else:
            if ($data->total() > 0):
                return view('artists', ['q' => $q, 'artists' => $data]);
            else:
                return view('artists', ['q' => $q, 'artists' => $data])->withMessage('No artists found. Try to search again!');
            endif;
        endif;
    }

    /**
     * Return autocompleted list of artists.
     *
     * @return Response
     */
    public function autocomplete(Request $request)
    {
        $data = [];

        if ($request->has('q')):
            $data = $this->artist->searchByName($request->q);
        endif;

        return response()->json($data);
    }

    /**
     * Retrieve artist songs.
     *
     * @return Response
     */
    public function songs($id) {
        $artist = $this->artist->get($id);
        $artist_songs = $artist->songs;
        $compilation_songs = $this->song->getArtistCompilationSongs($artist->artist);
        $combined_songs = $artist_songs->merge($compilation_songs);
        return response()->json($combined_songs);
    }
}
