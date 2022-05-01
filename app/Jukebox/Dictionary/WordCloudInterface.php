<?php

namespace App\Jukebox\Dictionary;

interface WordCloudInterface
{

    /**
     * Set word format and type.
     */
    public function setWord($word);

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

    /**
     * Retrieve the words
     *
     * @return array
     */
    public function get_words();

    /**
     * Remove words from word cloud.
     *
     * @param int $id
     *   The song id.
     */
    // private function removeWords($id);

    /**
     * Add words to the word cloud.
     *
     * @param int $id
     *   The song id.
     */
    // private function addWords($id);

    // private function isWord($w) ;

}