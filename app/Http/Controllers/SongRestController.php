<?php

namespace App\Http\Controllers;

use App\Jukebox\Song\SongInterface as Song;
use Illuminate\Http\Request;
use Storage;

class SongRestController extends Controller
{

    /**
     * The song interface
     *
     * @var App\Jukebox\Song\SongInterface
     */
    private $song;

    /**
     * Constructor
     */
    public function __construct(Song $song)
    {
        $this->song = $song;
    }

    /**
     * Display songs
     *
     * @return mixed
     */
    public function index(Request $request)
    {
        $songs = $this->song->all($request);
        if (empty($request->all()) || $request->has('page')):
            return view('songs', ['songs' => $songs]);
        endif;

        return $songs;
    }

    /**
     * Store a newly created song in the database
     *
     * @param Request request
     * @return Response
     */
    public function store(Request $request)
    {
        $this->song->createOrUpdate($request);
        return redirect('/songs');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit($id)
    {
        $song = $this->song->get($id ?? null);

        if (! $song):
            abort(404);
        endif;

        // Add this to the get function?
        if (! empty($song->cover_art)):
            $cover_art = unserialize($song->cover_art);
            $cover_art = $cover_art['api'];
        endif;
        if (empty($cover_art)):
            $cover_art = '/image/cover/' . $song->id;
        endif;
        return view('song', [
            'song'          => $song,
            'title'         => $song->title,
            'cover_art'     => $cover_art,
            'artists'       => json_encode($song->artists),
            'file_types'    => config('audio_file_formats'),
            'song_exists'   => Storage::disk(config('filesystems.partition'))->has(config('filesystems.media_directory') . $song->location),
        ]);
    }

}
