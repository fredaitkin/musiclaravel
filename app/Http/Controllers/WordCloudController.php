<?php

namespace App\Http\Controllers;

use App\Words\WordCloud;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Storage;

class WordCloudController extends Controller
{

    /**
     * Display songs
     *
     * @return Response
     */
    public function index(Request $request)
    {
        $wordCloud = WordCloud::paginate(10);
        return view('word_cloud', ['word_cloud' => $wordCloud]);
    }

}
