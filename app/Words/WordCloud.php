<?php

namespace App\Words;

use Illuminate\Database\Eloquent\Model;
use Kyslik\ColumnSortable\Sortable;

class WordCloud extends Model
{

    use Sortable;

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
     * Sortable columns
     *
     * @var array
     */
    protected $sortabe = [
        'word',
        'category',
        'count',
    ];

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    /**
     * Word cloud songs
     */
    public function songs()
    {
        return $this->belongsToMany('App\Music\Song\Song');
    }

}
