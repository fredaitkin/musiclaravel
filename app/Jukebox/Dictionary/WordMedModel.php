<?php

namespace App\Jukebox\Dictionary;

/**
 * WordNetModel is a simple class that sits on a open source dictionary.
 * 
 * The MySQL English Dictionary A dictionary with 176023 entries.
 * See https://sourceforge.net/projects/mysqlenglishdictionary/
 * 
 * Text was extracted from the files at http://www.mso.anu.edu.au/~ralph/OPTED/
 * and then parsed and stored in a 16MB MySQL database. 
 **/

use Illuminate\Database\Eloquent\Model;

class WordMedModel extends Model
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
