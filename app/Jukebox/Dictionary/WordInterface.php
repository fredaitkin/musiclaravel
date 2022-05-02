<?php

namespace App\Jukebox\Dictionary;

interface WordInterface
{

    /**
     * Is this a word.
     *
     * @param string $w Word
     * @return boolean
     */
    public function isWord($w);

    /**
     * Get dictionary definition for word/
     *
     * @param string $w Word
     * @return array
     */
    public function getDictionary($w);
}
