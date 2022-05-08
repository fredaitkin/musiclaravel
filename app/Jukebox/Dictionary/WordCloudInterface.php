<?php

namespace App\Jukebox\Dictionary;

interface WordCloudInterface
{

    /**
     * Process the song lyrics.
     *
     * @param string $word
     *   The word
     * @param string $action
     *   Add or remove
     * @param int $id
     *   Song id
     */
    public function process($lyrics, $action, $id);

    /**
     * Process and store the word.
     *
     * @param string $word
     *   The word.
     *
     * @return array
     */
    public function processWord($word);

}
