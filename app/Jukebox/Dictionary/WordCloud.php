<?php

namespace App\Jukebox\Dictionary;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Watson\Rememberable\Rememberable;

class WordCloud implements WordCloudInterface
{

    use Rememberable;

    /** Basic Routines */

    /**
     * Retrieve a word.
     *
     * @param int $id
     */
    public function get($id)
    {
        return WordCloudModel::find($id);
    }

    /**
     * Display word cloud
     *
     * @return Response
     */
    public function all(Request $request)
    {
        if (empty($request->all()) || $request->has('page')):
            return WordCloudModel::paginate(10);
        else:
            return $this->allByConstraints($request->all());
        endif;
    }

    /**
     * Get a list of words in wordcloud by constraints.
     *
     * @return array
     */
    public function allByConstraints(array $constraints = [])
    {
        $query = WordCloudModel::select('word_cloud.*');

        if (! empty($constraints['filter'])):
            return $this->getFilteredResults($constraints['filter']);
        endif;

        if (isset($constraints['songs']) && isset($constraints['id'])):
            return $this->getJsonResults($constraints['id']);
        endif;

        if (isset($constraints['like'])):
            $query->where("word", "LIKE", "%{$constraints['like']}%");
        endif;

        return $query->get();
    }

    /**
     * Update a word in the word cloud.
     *
     * @param Request $request
     */
    public function createOrUpdate(Request $request)
    {
        $validator = $request->validate([
            'id' => 'required',
            'word' => 'required',
        ]);

        if (isset($request->id)):
            $model = WordCloudModel::find($request->id);
        else:
            $model = new WordCloudModel();
        endif;

        $model->word = $request->word;
        $model->is_word = $request->is_word ? 1 : 0;
        $model->variant_of = $request->variant_of;

        $model->save();

        // Make any updates to categories
        if (empty($request->categories)):
            $request->categories = [];
        endif;
        // Name quick set.
        if (isset($request->set_name)):
            $request->categories[] = 24;
        endif;
        $inserts = array_diff($request->categories, $model->category_array);
        foreach($inserts as $id):
            $model->categories()->attach(['category_id' => $id]);
        endforeach;
        $deletes = array_diff($model->category_array, $request->categories);
        foreach($deletes as $id):
            $model->categories()->detach(['category_id' => $id]);
        endforeach;
    }

    /** Utility Routines */

    /**
     * Update a word in the word cloud.
     *
     * @param array $word
     */
    public function dynamicStore(array $word)
    {
        // Add validation
        $model = new WordCloudModel();
        $model->word = $word['word'];
        $model->is_word = $word['is_word'];
        
        $model->save();

        // Add word song references
        foreach($word['song_ids'] as $song_id):
            $wordCloud->songs()->attach(['song' => $song_id]);
        endforeach;
    }

    /** Other Routines */

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
    public function process($lyrics, $action, $id)
    {
        $lyrics = str_replace([PHP_EOL, ' '], [' ', ' '], $lyrics);
        $words = explode(' ', $lyrics);

        $this->words = [];
        foreach ($words as $word):
            $this->processWord(mb_strtolower($word));
        endforeach;

        if ($action == 'subtract'):
            $this->removeWords($id);
        else:
            $this->addWords($id);
        endif;
    }

    /**
     * Process and store the word.
     *
     * @param string $word
     *   The word.
     *
     * @return array
     */
    public function processWord($word) {
        // Clean up text.
        $chars_to_replace = [',', '.', '"', ' ', '!', '?', '[', ']', '(', ')', '{', '}', '&', "''", '*', ';', '…', '~', '​', ' '];
        $word = str_replace(
            $chars_to_replace,
            array_fill(0, count($chars_to_replace), ''),
            $word
        );
        // Replace ticks and curly quotes etc.
        $word = str_replace(['`', '‘', '’', '–'], ["'", "'", "'", '-'], $word);
        // Strip certain characters from the start and the end of words.
        $chars_to_trim = " :-/\0\t\n\x0B\r";
        $word = trim($word, $chars_to_trim);
        if (! empty($word) && ! preg_match('/^[-]+$/', $word)):
            // 'accattone'
            if($word[0] == "'" && $word[strlen($word) - 1] == "'"):
                $word = substr($word, 1, strlen($word) - 2);
                if ($word === 'n'):
                    $word = "'n";
                endif;
            endif;
            if (! isset($this->words[$word])):
                $this->words[$word] = 1;
            else:
                $this->words[$word] += 1;
            endif;
        endif;
    }

    /**
     * Remove words from word cloud.
     *
     * @param int $id
     *   The song id.
     */
    private function removeWords($id) {
        foreach($this->words as $word => $count):
            $wordCloud = WordCloudModel::whereRaw('LOWER(word) = ?', $word)->first();
            if($wordCloud):
                $wordCloud->count = $wordCloud->count - $count;
                $wordCloud->save();
                $wordCloud->songs()->detach(['song' => $id]);
            endif;
        endforeach;
    }

    /**
     * Add words to the word cloud.
     *
     * @param int $id
     *   The song id.
     */ 
    private function addWords($id) {
        foreach($this->words as $word => $count):
            $wordCloud = WordCloudModel::whereRaw('LOWER(word) = ?', mb_strtolower($word))->first();
            if($wordCloud):
                $wordCloud->count += $count;
            else:
                $wordCloud = WordCloudModel::create([
                    'word' => $word,
                    'is_word' => $this->isWord($word),
                    'created_at' => Carbon::now(),
                    'count' => $count,
                ]);
            endif;
            $wordCloud->save();
            $wordCloud->songs()->attach(['song' => $id]);
        endforeach;
    }

    /**
     * A filtered, paginated list of words.
     *
     * @param string $filtered
     */
    private function getFilteredResults($filter)
    {
        return WordCloudModel::select('word_cloud.*')
            ->leftJoin('word_category', 'word_cloud.id', '=', 'word_category.word_cloud_id')
            ->leftJoin('category', 'word_category.category_id', '=', 'category.id')
            ->where('word', 'like', '%' . $filter . '%')
            ->orWhere('category.category', 'like', '%' . $filter . '%')
            ->groupBy('id')
            ->paginate(10);
    }

    /**
     * A json encoded list of songs that use the word.
     *
     * @param int $ind
     */
    private function getJsonResults($id)
    {
        $wordCloud = WordCloudModel::find($id);
        foreach($wordCloud->songs as $song):
            $data[] = array('id' => $song->id, 'song' => $song->title, 'artist' => $song->artists[0]->artist, 'lyrics' => $song->lyrics);
        endforeach;
        usort($data, [$this, 'sortSongs']);
        return json_encode($data);
    }

    /**
     * Sort an array of songs by song title.
     */
    private function sortSongs($a, $b)
    {
        return strcmp($a["song"], $b["song"]);
    }

    private function isWord($w) {
        try {
            if (WordNet::isWord($w)):
                return true;
            else:
                return WordMED::isWord($w);
            endif;
        } catch (Exception $e) {
            return false;
        }
    }

}
