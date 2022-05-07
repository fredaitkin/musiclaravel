<?php

namespace App\Http\Controllers;

use App\Jukebox\Artist\ArtistInterface as Artist;
use App\Jukebox\Song\SongInterface as Song;
use App\Traits\StoreImageTrait;
use Illuminate\Http\Request;
use URL;

class ArtistRestController extends Controller
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
     * Display artists
     *
     * @return Response
     */
    public function index(Request $request)
    {
        return view('artists', ['artists' => $this->artist->all($request)]);
    }

    /**
     * Show the form for creating a new artist
     *
     * @return Response
     */
    public function create()
    {
        return view('artist', ['title' => 'Add Artist', 'countries' => config('countries')]);
    }

    /**
     * Store a newly created artist in the database
     *
     * @param Request $request
     * @return Response
     */
    public function store(Request $request)
    {
        $this->artist->createOrUpdate($request);
        return redirect('/artists');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */ 
    public function edit($id)
    {
        $artist = $this->artist->get($id ?? null);
        $albums = null;
        if ($artist->artist === 'Compilations'):
            $albums = array_merge(['' => 'Please Select...'], $this->artist->getArtistAlbums($id));
        endif;
        if (! empty($artist->photo)):
            if (strpos($artist->photo, 'cdn') === false):
                $artist->photo = URL::to('/') . '/storage/artists/' . $artist->photo;
            endif;
        endif;
        return view('artist', [
            'title'     => $artist->artist,
            'artist'    => $artist,
            'albums'    => $albums,
            'songs'     => $this->song->getArtistSongs($id, $artist->artist),
            'countries' => array_merge(['' => 'Please Select...'], config('countries')),
        ]);
    }

    /**
     * Remove the artist and all their songs from the database
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy(Request $request, $id)
    {
        $this->artist->destroy($id);
    }

}
