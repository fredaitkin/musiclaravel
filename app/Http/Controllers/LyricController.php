<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Music\Song\Song;
use App\Words\Word;
use App\Words\WordCloud;
use Illuminate\Http\Request;

class LyricController extends Controller
{

    /**
     * Store song lyrics in the database
     *
     * @param Request request
     * @return Response
     */
    public function store(Request $request)
    {
        $validator = $request->validate([
            'id' => 'required|max:255',
        ]);

        $song = Song::find($request->id);

        if ($request->lyrics != $song->lyrics):
            $words = explode(' ', str_replace([PHP_EOL], [' '], $song->lyrics));
            foreach ($words as $w):
                $word = new Word();
                $word->process($w, 'subtract');
            endforeach;
            $words = explode(' ', str_replace([PHP_EOL], [' '], $request->lyrics));
            foreach ($words as $w):
                $word = new Word();
                $word->process($w, 'add');
            endforeach;
        endif;

        return redirect('/songs');
    }

    /**
     * Show the song lyrics
     *
     * @param int $id
     * @return string
     */
    public function show($id)
    {
        $song = Song::find($id);
        if ($song):
            return view('lyrics', ['song' => $song]);
        else:
            abort(404);
        endif;
    }

}
