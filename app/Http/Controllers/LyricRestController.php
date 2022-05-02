<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Jukebox\Dictionary\WordCloudInterface as WordCloud;
use App\Jukebox\Song\SongInterface as Song;
use Illuminate\Http\Request;

class LyricRestController extends Controller
{

    /**
     * The song interface
     *
     * @var App\Jukebox\Song\SongInterface
     */
    private $song;

    /**
     * The wordcloude interface
     *
     * @var App\Jukebox\Dictionary\WordCloudInterface
     */
    private $wordCloud;

    /**
     * Constructor
     */
    public function __construct(Song $song, WordCloud $wordCloud)
    {
        $this->song = $song;
        $this->wordCloud = $wordCloud;
    }

    /**
     * Show the song lyrics
     *
     * @param int $id
     * @return string
     */
    public function index($id)
    {
        $song = $this->song->get($id);
        if ($song):
            return view('lyrics', ['song' => $song]);
        else:
            abort(404);
        endif;
    }

    /**
     * Store song lyrics in the database
     *
     * @param Request request
     * @return Response
     */
    public function store(Request $request)
    {
        $validator = $request->validate([
            'id' => 'required|integer',
        ]);

        $song = $this->song->get($request->id);

        if ($request->lyrics != $song->lyrics):
            $this->wordCloud->process($song->lyrics, 'subtract', $song->id);
            $this->wordCloud->process($request->lyrics, 'add', $song->id);
            $song->lyrics = $request->lyrics;
            $song->save();
        endif;

        return redirect('/songs');
    }

}
