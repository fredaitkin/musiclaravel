<?php

namespace App\Http\Controllers;

use App\Jukebox\Dictionary\CategoryInterface as Category;
use Illuminate\Http\Request;

class CategoryResourceController extends Controller
{

    /**
     * The Category interface
     *
     * @var App\Jukebox\Dictionary\CategoryInterface
     */
    private $category;

    /**
     * Constructor
     */
    public function __construct(Category $category)
    {
        $this->category = $category;
    }

    /**
     * Return categories
     *
     * @return Response
     */
    public function categories(Request $request)
    {
        return $this->category->categories($request);
    }

}
