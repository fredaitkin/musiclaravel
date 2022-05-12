<?php

namespace App\Jukebox\Dictionary;

interface WordInterface
{

    /**
     * Is this a word.
     *
     * @param  string $w
     * @return bool
     */
    public static function isWord($w);

    /**
     * Get dictionary definition for word
     *
     * @param  string $w
     * @return array
     */
    public function getDictionary($w);

}
