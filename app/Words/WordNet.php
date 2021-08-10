<?php

namespace App\Words;

use Illuminate\Database\Eloquent\Model;

class WordNet extends Model
// class WordNet extends Word
{

    protected $connection = 'mysql3';

    protected $table = 'wn_synset';

   /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $synset_id;

    /**
     * @var integer
     */
    protected $w_num;

    /**
     * @var string
     */
    protected $ss_type;

    /**
     * The word.
     *
     * @var string
     */
    protected $word;

    /**
     *
     * @var integer
     */
    protected $sense_number;

    /**
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
        $word = WordNet::where(["word" => $w])->get()->first();
        return isset($word);
    }

}
