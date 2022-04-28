<?php

namespace App\Music\Dictionary;

use Illuminate\Database\Eloquent\Model;
use Kyslik\ColumnSortable\Sortable;

class WordCloudModel extends Model
{

    use Sortable;

    /**
     * Use timestamps
     *
     * @var bool
     */
    public $timestamps = true;

    protected $table = 'word_cloud';

    /**
     * The primary key for the model.
     *
     * @var integer
     */
    protected $id;

    /**
     * The word.
     *
     * @var string
     */
    protected $word;

    /**
     * Number of times the word appears in lyrics.
     *
     * @var integer
     */
    protected $count;

    /**
     * Recognized as word by the two dictionary databases.
     *
     * @var bool
     */
    protected $is_word;

    /**
     * Category.
     *
     * @var string
     */
    protected $category;

    /**
     * The id of the word this word is the variant of.
     *
     * @var integer
     */
    protected $variant_of;

    /**
     * Created date.
     *
     * @var datetime
     */
    protected $created_at;

    /**
     * Updated date.
     *
     * @var datetime
     */
    protected $updated_at;

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    /**
     * Word cloud categories
     */
    public function categories()
    {
        // Specify table name as it does not follow the default laravel naming convention
        return $this->belongsToMany('App\Category\Category', 'word_category', 'word_cloud_id');
    }

    /**
     * Word cloud categories for display
     */
    public function getCategoryDisplayAttribute()
    {
        $categories = [];
        foreach ($this->categories as $category) {
            $categories[] = $category->category;
        }
        return implode(',', $categories);
    }

    /**
     * Word cloud category ids
     */
    public function getCategoryIdsAttribute()
    {
        $categories = [];
        foreach ($this->categories as $category) {
            $categories[] = $category->id;
        }
        return implode(',', $categories);
    }

    /**
     * Get the category ids as an array
     * @return string
     */
    public function getCategoryArrayAttribute()
    {
        $categories = [];
        foreach ($this->categories as $category) {
            $categories[] = $category->id;
        }
        return $categories;
    }

    /**
     * Get variant as word
     *
     * @return string
     */
    public function getVariantAttribute()
    {
        $word = json_decode($this);
        $variant = WordCloudModel::select('word')->where('id', $word->variant_of)->get()->toArray();
        return $variant[0]['word'] ?? '';
    }

    /**
     * Word cloud songs
     */
    public function songs()
    {
        return $this->belongsToMany('App\Jukebox\Song\SongModel', 'song_word_cloud', 'word_cloud_id', 'song_id');
    }

}
