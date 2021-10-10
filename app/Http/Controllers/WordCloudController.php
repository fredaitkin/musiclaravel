<?php

namespace App\Http\Controllers;

use App\Words\WordCloud;
use App\Music\Song\Song;
use DB;
use Illuminate\Http\Request;

class WordCloudController extends Controller
{

    /**
     * Display word cloud
     *
     * @return Response
     */
    public function index(Request $request)
    {
        $filter = $request->query('filter');

        if (! empty($filter)) {
            $words = WordCloud::sortable()
                ->where('category', 'like', '%' . $filter . '%')
                ->orWhere('word', 'like', '%' . $filter . '%')
                ->paginate(10);
        } else {
            $words = WordCloud::sortable()
                ->paginate(10);
        }

        $view = view('word_cloud', [
            'word_cloud' => $words,
            'filter' => $filter,
        ]);

        if ($words->isEmpty()) {
            $view->withMessage('No words found. Try to search again!');
        }

        return $view;
    }

    public function songs(Request $request)
    {
        $songs = DB::table('song_word_cloud')
             ->select('song_id')
             ->where('word_cloud_id', $request->get('id'))
             ->get();

        $data = [];
        foreach($songs as $song):
            $song = Song::find($song->song_id);
            $data[] = array('song' => $song->title, 'artist' => $song->artists[0]->artist);
        endforeach;

        return json_encode($data);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit($id)
    {
        $word_cloud = WordCloud::find($id);
        return view('word', ['word_cloud' => $word_cloud]);
    }

}
