<?php

namespace App\Words;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class Word extends Model
{

    protected $connection = 'mysql2';

    protected $table = 'wn_synset';

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $synset_id;

    /**
     * 
     *
     * @var integer
     */
    protected $w_num;

    /**
     * The word.
     *
     * @var string
     */
    protected $word;

    /**
     *
     *
     * @var string
     */
    protected $ss_type;

    /**
     *
     *
     * @var integer
     */
    protected $sense_number;

    /**
     *
     *
     * @var integer
     */
    protected $tag_count;

    /**
     * Is this a word.
     *
     * @param string $w Word
     * @return boolean
     */
    public static function isWord($w)
    {
        $word = Word::where(["word" => $w])->get()->first();
        return isset($word);
    }

}
