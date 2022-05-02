<?php

namespace App\Jukebox\Dictionary;

class WordMed
{

    /**
     * Is this a word.
     *
     * @param string $w Word
     * @return boolean
     */
    public static function isWord($w)
    {
        $word = WordMedModel::whereRAW('binary LOWER(word) = binary ?', $w)->get()->first();
        return isset($word);
    }

    public static function getDictionary($w)
    {
        $dictionary = [];

        $word = WordMedModel::whereRAW('binary LOWER(word) = binary ?', $w)->get();

        if (empty($word[0])):
            return $dictionary;
        endif;

        $glossary = [];
        foreach($word as $definition):
            $glossary[] = ['type' => $definition['wordtype'], 'definition' => $definition['definition']];
        endforeach;
        $dictionary['word'] = $word[0]['word'];
        $dictionary['glossary'] = $glossary;
        return $dictionary;
    }

}
