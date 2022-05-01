<?php

namespace App\Http\Controllers;

use App\Music\Dictionary\CategoryInterface as Category;
use Illuminate\Http\Request;

class CategoryResourceController extends Controller
{

    /**
     * The Category interface
     *
     * @var App\Music\Dictionary\CategoryInterface
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
        return $this->category->all($request);
    }

}
