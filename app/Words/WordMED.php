<?php

namespace App\Words;

use Illuminate\Database\Eloquent\Model;

class WordMED extends Model
// class WordMED extends Word
{

    protected $connection = 'mysql2';

    protected $table = 'entries';

    /**
     * The word.
     *
     * @var string
     */
    protected $word;

    /**
     * The type of word - n. adv. etc
     *
     * @var string
     */
    protected $wordtype;

    /**
     * The word definition
     * 
     * @var string
     */
    protected $definition;

    /**
     * Is this a word.
     *
     * @param string $w Word
     * @return boolean
     */
    public static function isWord($w)
    {
        $word = WordMED::whereRAW('binary LOWER(word) = binary ?', $w)->get()->first();
        return isset($word);
    }

}
