<?php

namespace App\Music\Dictionary;

use Illuminate\Http\Request;

interface CategoryInterface
{

    public function all();

    public function categories(Request $request);

}
