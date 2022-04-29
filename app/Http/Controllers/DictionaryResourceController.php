<?php

namespace App\Http\Controllers;

use App\Music\Dictionary\WordInterface as WordMed;
use App\Music\Dictionary\WordInterface as WordNet;
use Illuminate\Http\Request;

class DictionaryResourceController extends Controller
{

    /**
     * The WordMed interface
     *
     * @var App\Music\Dictionary\WordInterface
     */
    private $wordMed;

    /**
     * The WordNet interface
     *
     * @var App\Music\Dictionary\WordInterface
     */
    private $wordNet;

    /**
     * Constructor
     */
    public function __construct(WordMed $wordMed, WordNet $wordNet)
    {
        $this->wordMed = $wordMed;
        $this->wordNet = $wordNet;
    }

    /**
     * Display dictionary information for a word
     *
     * @return Response
     */
    public function dictionary(Request $request)
    {
        $dictionaries = [];
        $dictionaries[] = $this->wordMed->getDictionary($request->get('word'));
        $dictionaries[] = $this->wordNet->getDictionary($request->get('word'));
        return $dictionaries;
    }

}