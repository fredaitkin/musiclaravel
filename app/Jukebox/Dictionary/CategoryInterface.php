<?php

namespace App\Jukebox\Dictionary;

use Illuminate\Http\Request;

interface CategoryInterface
{
    /* 
     * Retrieve all categories
     *
     * @param  Request $request
     */
    public function all(Request $request);

}
