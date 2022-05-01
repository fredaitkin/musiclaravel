<?php

namespace App\Jukebox\Dictionary;

class WordNet implements WordInterface
{

    /**
     * Is this a word.
     *
     * @param string $w Word
     * @return boolean
     */
    public function isWord($w)
    {
        $word = WordNetModel::where(["word" => $w])->get()->first();
        return isset($word);
    }

    public function getDictionary($w)
    {
        $dictionary = [];

        $word = WordNetModel::where(["word" => $w])->get();

        if (empty($word[0])):
            return $dictionary;
        endif;

        $glossary = [];
        foreach($word as $definition):
            $glossary[] = ['type' => $definition['ss_type'], 'definition' => $definition['glossary']->gloss];
        endforeach;
        $dictionary['word'] = $word[0]['word'];
        $dictionary['glossary'] = $glossary;
        return $dictionary;
    }
}
