<?php

namespace App\Http\Controllers;

use App\Jukebox\Song\SongInterface as Song;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;
use Storage;

class SongRestController extends Controller
{

    /**
     * The media directory
     *
     * @var string
     */
    // TODO make me a trait?
    private $media_directory;

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
        $this->media_directory = Redis::get('media_directory');
        $this->song = $song;
    }

    /**
     * Display songs
     *
     * @return mixed
     */
    public function index(Request $request)
    {
        if (empty($request->all()) || $request->has('page')):
            $songs = $this->song->all();
            return view('songs', ['songs' => $songs]);
        endif;

        return $this->song->songs($request);
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
            $cover_art = '/cover/' . $song->id;
        endif;
        return view('song', [
            'song'          => $song,
            'title'         => $song->title,
            'cover_art'     => $cover_art,
            'artists'       => json_encode($song->artists),
            'file_types'    => config('audio_file_formats'),
            'song_exists'   => Storage::disk(config('filesystems.partition'))->has($this->media_directory . $song->location),
        ]);
    }

}
