<?php

namespace App\Music\Dictionary;

use Illuminate\Database\Eloquent\Model;

class WordMED extends Model
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

}
