<?php

namespace App\Http\Controllers;

use App\Jukebox\Dictionary\WordMed;
use App\Jukebox\Dictionary\WordNet;
use Illuminate\Http\Request;

class DictionaryResourceController extends Controller
{

    /**
     * Display dictionary information for a word
     *
     * @return Response
     */
    public function dictionary(Request $request)
    {
        $dictionaries = [];
        $dictionaries[] = WordMed::getDictionary($request->get('word'));
        $dictionaries[] = WordNet::getDictionary($request->get('word'));
        return $dictionaries;
    }

}
