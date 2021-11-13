<?php

use App\Category\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Category::create(['category' => 'alphabet']);
        Category::create(['category' => 'Arabic']);
        Category::create(['category' => 'band']);
        Category::create(['category' => 'brand']);
        Category::create(['category' => 'capitalized']);
        Category::create(['category' => 'country']);
        Category::create(['category' => 'Creole']);
        Category::create(['category' => 'day']);
        Category::create(['category' => 'Dutch']);
        Category::create(['category' => 'French']);
        Category::create(['category' => 'Gaelic']);
        Category::create(['category' => 'German']);
        Category::create(['category' => 'Greek']);
        Category::create(['category' => 'Hebrew']);
        Category::create(['category' => 'honorific']);
        Category::create(['category' => 'Irish']);
        Category::create(['category' => 'Italian']);
        Category::create(['category' => 'Jamaican']);
        Category::create(['category' => 'Japanese']);
        Category::create(['category' => 'Latin']);
        Category::create(['category' => 'made_up']);
        Category::create(['category' => 'month']);
        Category::create(['category' => 'movie']);
        Category::create(['category' => 'name']);
        Category::create(['category' => 'nationality']);
        Category::create(['category' => 'object']);
        Category::create(['category' => 'organisation']);
        Category::create(['category' => 'place']);
        Category::create(['category' => 'Portugese']);
        Category::create(['category' => 'religion']);
        Category::create(['category' => 'Russian']);
        Category::create(['category' => 'Sanskrit']);
        Category::create(['category' => 'Scottish']);
        Category::create(['category' => 'Sengalese']);
        Category::create(['category' => 'Spanish']);
        Category::create(['category' => 'state']);
        Category::create(['category' => 'street']);
        Category::create(['category' => 'Swahili']);
        Category::create(['category' => 'town']);
        Category::create(['category' => 'tv']);
        Category::create(['category' => 'Welsh']);
        Category::create(['category' => 'Zulu']);
    }
}
