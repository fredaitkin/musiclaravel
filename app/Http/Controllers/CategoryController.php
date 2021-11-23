<?php

namespace App\Http\Controllers;

use App\Category\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{

    /**
     * Return categories
     *
     * @return Response
     */
    public function categories(Request $request)
    {
        $data = [];
        if ($request->has('q')) {
            $data = Category::select('id', 'category as text')->where('category', 'LIKE', '%' . $request->q . '%')->get();
        }
        return response()->json($data);
    }

}
