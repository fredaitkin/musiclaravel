<?php

namespace App\Http\Controllers;

use App\Words\WordMED;
use App\Words\WordNet;
use Illuminate\Http\Request;

class DictionaryController extends Controller
{

    /**
     * Display dictionary information for a word
     *
     * @return Response
     */
    public function dictionary(Request $request)
    {
        $dictionaries = [];
        $dictionaries[] = WordNet::getDictionary($request->get('word'));
        $dictionaries[] = WordMED::getDictionary($request->get('word'));
        return $dictionaries;
    }

}