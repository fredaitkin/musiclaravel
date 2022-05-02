<?php

namespace App\Jukebox\Dictionary;

use Illuminate\Http\Request;

class Category implements CategoryInterface
{

    /**
     * Get categories
     *
     */
    public function all(array $constraints = null)
    {
        return CategoryModel::get();
    }

    /**
     * Get categories
     *
     * @return Response
     */
    public function categories(Request $request)
    {
        $data = [];
        if ($request->has('q')):
            $data = CategoryModel::select('id', 'category as text')->where('category', 'LIKE', '%' . $request->q . '%')->get();
        endif;
        return response()->json($data);
    }

}
