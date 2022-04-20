<?php

namespace App\Words;

use App\Words\GlossaryNet;
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

    /**
     * Get the glossary associated with the word.
     */
    public function glossary()
    {
        return $this->hasOne(GlossaryNet::class, 'synset_id', 'synset_id');
    }

    public function getDictionary($w)
    {
        $word = static::where(["word" => $w])->get();
        $glossary = [];
        foreach($word as $definition):
            $glossary[] = ['type' => $definition['ss_type'], 'definition' => $definition['glossary']->gloss];
        endforeach;
        $dictionary = new \stdClass();
        $dictionary->word = $word[0]['word'];
        $dictionary->glossary = $glossary;
        return $dictionary;
    }
}
