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
        $filter = $request->query('filter');

        if (! empty($filter)) {
            $words = WordCloud::sortable()
                ->where('category', 'like', '%' . $filter . '%')
                ->orWhere('word', 'like', '%' . $filter . '%')
                ->paginate(10);
        } else {
            $words = WordCloud::sortable()
                ->paginate(10);
        }

        return view('word_cloud', [
            'word_cloud' => $words,
            'filter' => $filter,
        ]);

    }


}
