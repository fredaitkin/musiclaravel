<?php

namespace App\Http\Controllers;

use App\Category\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{

    /**
     * Retrieve categories
     *
     * @param Request $request
     * @return Response
     */
    public function categories(Request $request)
    {
       return Category::select(['id', 'category as value'])->get();
    }

}
