<?php

namespace App\Music\Dictionary;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class Category extends Model
{

    /**
     * Get categories
     *
     * @return Response
     */
    public function categories(Request $request)
    {
        $data = [];
        if ($request->has('q')) {
            $data = CategoryModel::select('id', 'category as text')->where('category', 'LIKE', '%' . $request->q . '%')->get();
        }
        return response()->json($data);
    }

}
