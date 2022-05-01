<?php

namespace App\Http\Controllers;

use App\Jukebox\Dictionary\WordCloudInterface as WordCloud;
use Illuminate\Http\Request;

class WordCloudResourceController extends Controller
{

    /**
     * The wordcloude interface
     *
     * @var App\Jukebox\Dictionary\WordCloudInterface
     */
    private $wordCloud;

    /**
     * Constructor
     */
    public function __construct(WordCloud $wordCloud)
    {
        $this->wordCloud = $wordCloud;
    }


    /**
     * Search for word.
     *
     * @return \Illuminate\Http\Response
     */
    public function autocomplete(Request $request)
    {
        $words = $this->wordCloud->getWords(['like' => $request->search]);
        $data = [];
        foreach($words as $word):
            $data[] = ['value' => $word->id, 'label' => $word->word];
        endforeach;
        return response()->json($data);
    }
}
