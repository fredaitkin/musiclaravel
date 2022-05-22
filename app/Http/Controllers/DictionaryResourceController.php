<?php

namespace App\Http\Controllers;

use App\Jukebox\Dictionary\DictionaryInterface as Dictionary;
use Illuminate\Http\Request;

class DictionaryResourceController extends Controller
{

    /**
     * The dictionary interface
     *
     * @var App\Jukebox\Dictionary\DictionaryInterface
     */
    private $dictionary;

    /**
     * Constructor
     */
    public function __construct(Dictionary $dictionary)
    {
        $this->dictionary = $dictionary;
    }

    /**
     * Display dictionary information for a word
     *
     * @return Response
     */
    public function dictionary(Request $request)
    {
        return $this->dictionary->getDictionary($request->get('word'));
    }

}
