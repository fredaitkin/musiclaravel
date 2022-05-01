<?php

namespace App\Http\Controllers;

use App\Jukebox\Dictionary\WordCloudInterface as WordCloud;
use DB;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class WordCloudResourceController extends Controller
{

    /**
     * The wordcloude interface
     *
     * @var App\Jukebox\Dictionary\WordCloudInterface
     */
    private $wordcloud;

    /**
     * Constructor
     */
    public function __construct(WordCloud $wordcloud)
    {
        $this->wordcloud = $wordcloud;
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
