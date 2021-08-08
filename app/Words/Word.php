<?php

namespace App\Words;

use Illuminate\Database\Eloquent\Model;

class Word extends Model
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
        $word = Word::where(["word" => $w])->get()->first();
        return isset($word);
    }

}
