<?php

namespace App\Music\Dictionary;

interface WordInterface
{

    /**
     * Is this a word.
     *
     * @param string $w Word
     * @return boolean
     */
    public function isWord($w);

    public function getDictionary($w);
}
