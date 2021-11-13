<?php

namespace App\Http\Controllers;

use App\Music\Song\Song;
use App\Words\WordCloud;
use DB;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

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
            // @todo add category search
            $words = WordCloud::sortable()
                ->where('word', 'like', '%' . $filter . '%')
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

    /**
     * Retrieve songs (and artists) that feature a word.
     */
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

        usort($data, [$this, 'sortSongs']);

        return json_encode($data);
    }

    /**
     * Sort an array of songs by song title.
     */
    private function sortSongs($a, $b)
    {
        return strcmp($a["song"], $b["song"]);
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

    /**
     * Update a word in the word cloud.
     *
     * @param Request $request
     */
    public function store(Request $request)
    {
        $validator = $request->validate([
            'id' => 'required',
            'word' => 'required',
            'category' => Rule::in(WordCloud::getCategories()),
        ]);

        $wordCloud = [];
        $wordCloud['word'] = $request->word;
        $wordCloud['is_word'] = $request->is_word ? 1 : 0;
        $wordCloud['category'] = $request->category;
        $wordCloud['variant_of'] = $request->variant_of;

        WordCloud::where('id', $request->id)->update($wordCloud);

        return view('word_cloud', [
            'word_cloud' => WordCloud::sortable()->paginate(10),
            'filter' => '',
        ]);
    }

    /**
     * Search for word.
     *
     * @return \Illuminate\Http\Response
     */
    public function autocomplete(Request $request)
    {
        $data = WordCloud::select("id as value", "word as label")
            ->where("word", "LIKE", "{$request->search}%")
            ->get();
        return response()->json($data);
    }
}
