<?php

namespace App\Http\Controllers;

use App\Words\WordCloud;
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
        return view('word_cloud', ['word_cloud' => WordCloud::getWordCloud()]);
    }

}
