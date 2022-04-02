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
            'id' => 'required|integer',
        ]);

        $song = Song::find($request->id);

        if ($request->lyrics != $song->lyrics):
            $word_cloud = new WordCloud();
            $word_cloud->process($song->lyrics, 'subtract', $song->id);
            $word_cloud = new WordCloud();
            $word_cloud->process($request->lyrics, 'add', $song->id);
            $song->lyrics = $request->lyrics;
            $song->save();
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

    /**
     * Get author lyrics
     *
     * @param Request request
     * @return Response
     */
    public function artist(Request $request)
    {
        $query = Song::select('songs.id', 'songs.title');

        if(isset($request->artist)):
            $artist = $request->artist;
            $query->whereHas('artists', function($q) use($artist, $request) {
                if($request->exact_match === 'true'):
                    $q->where('artist', $artist);
                else:
                    $q->where('artist', 'LIKE', '%' . $artist . '%');
                endif;
            });
        endif;

        if(isset($request->exempt)):
            $query->whereNotIn('songs.id', $request->exempt);
        endif;

        if(isset($request->empty)):
            $query->whereIn('lyrics', ['','unavailable']);
        endif;

        return $query->get();
    }

}
