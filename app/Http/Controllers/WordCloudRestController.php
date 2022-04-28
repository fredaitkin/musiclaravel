<?php

namespace App\Http\Controllers;

use App\Music\Dictionary\WordCloudInterface as WordCloud;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class WordCloudRestController extends Controller
{

    /**
     * The wordcloude interface
     *
     * @var App\Music\Dictionary\WordCloudInterface
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
     * Display word cloud
     *
     * @return Response
     */
    public function index(Request $request)
    {
        if (empty($request->all()) || $request->has('page') || $request->has('filter')):
            $words = $this->wordcloud->all($request);

            $view = view('word_cloud', [
                'word_cloud' => $words,
                'filter' => $request->query('filter'),
            ]);

            if ($words->isEmpty()) {
                $view->withMessage('No words found. Try to search again!');
            }

            return $view;
        endif;

        return $this->wordcloud->songs($request);

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit($id, Request $request)
    {
        $wordcloud = $this->wordcloud->get($id);
        return view('word', [
            'page'          => $request->page,
            'word_cloud'    => $wordcloud,
            'categories'    => json_encode($wordcloud->categories),
        ]);
    }

    /**
     * Update a word in the word cloud.
     *
     * @param Request $request
     */
    public function store(Request $request)
    {
        $this->wordcloud->createOrUpdate($request);
        return view('word_cloud', [
            'word_cloud' => $this->wordcloud->all($request),
            // 'word_cloud' => WordCloudModel::sortable()->paginate(10, '*', 'page', $request->page),
            'filter' => '',
        ]);
    }

}
