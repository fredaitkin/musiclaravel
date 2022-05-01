<?php

namespace App\Music\Dictionary;

use Carbon\Carbon;
use DB;
use App\Jukebox\Song\SongModel as Song;
use Illuminate\Http\Request;
use Watson\Rememberable\Rememberable;

class WordCloud implements WordCloudInterface
{

    use Rememberable;

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
        $filter = $request->query('filter');

        if (! empty($filter)) {
            $words = WordCloudModel::sortable()
                ->select('word_cloud.*')
                ->leftJoin('word_category', 'word_cloud.id', '=', 'word_category.word_cloud_id')
                ->leftJoin('category', 'word_category.category_id', '=', 'category.id')
                ->where('word', 'like', '%' . $filter . '%')
                ->orWhere('category.category', 'like', '%' . $filter . '%')
                ->groupBy('id')
                ->paginate(10);
        } else {
            $words = WordCloudModel::sortable()
                ->paginate(10);
        }

        return $words;
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
        if (isset($request->set_name)) {
            $request->categories[] = 24;
        }
        $inserts = array_diff($request->categories, $model->category_array);
        foreach($inserts as $id):
            $model->categories()->attach(['category_id' => $id]);
        endforeach;
        $deletes = array_diff($model->category_array, $request->categories);
        foreach($deletes as $id):
            $model->categories()->detach(['category_id' => $id]);
        endforeach;
    }

    /**
     * Get word cloud
     *
     * @return Response
     */
    public function wordCloud(array $contraints = null)
    {
        return WordCloudModel::get();
    }

    /**
     * Retrieve songs (and artists) that feature a word.
     * TODO Separate into song attribute ++
     */
    public function songs(Request $request)
    {
        $songs = DB::table('song_word_cloud')
             ->select('song_id')
             ->where('word_cloud_id', $request->get('id'))
             ->get();

        $data = [];
        foreach($songs as $song):
            // $song = DB::table('songs')->select('*')->where('id', $song->song_id)->get();
            // TODO take out of WordCloud model
            $song = Song::find($song->song_id);
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

    /**
     * Set word format and type.
     */
    public function setWord($word)
    {
        $word = mb_strtolower($word);

        if (isset(config('countries_expanded')[$word])):
            return ['word' => config('countries_expanded')[$word], 'category' => 'country'];
        endif;

        if (isset(config('states_expanded')[$word])):
            return ['word' => config('states_expanded')[$word], 'category' => 'state'];
        endif;

        if (isset(config('towns')[$word])):
            return ['word' => config('towns')[$word], 'category' => 'town'];
        endif;

        if (isset(config('places')[$word])):
            return ['word' => config('places')[$word], 'category' => 'places'];
        endif;

        if (isset(config('streets')[$word])):
            return ['word' => config('streets')[$word], 'category' => 'street'];
        endif;

        if (isset(config('months_expanded')[$word])):
            return ['word' => config('months_expanded')[$word], 'category' => 'month'];
        endif;

        if (isset(config('days_expanded')[$word])):
            return ['word' => config('days_expanded')[$word], 'category' => 'day'];
        endif;

        if (isset(config('names')[$word])):
            return ['word' => config('names')[$word], 'category' => 'name'];
        endif;

        if (isset(config('honorifics')[$word])):
            return ['word' => config('honorifics')[$word], 'category' => 'honorific'];
        endif;

        if (isset(config('brands')[$word])):
            return ['word' => config('brands')[$word], 'category' => 'brand'];
        endif;

        if (isset(config('organisations')[$word])):
            return ['word' => config('organisations')[$word], 'category' => 'organisation'];
        endif;

        if (isset(config('acronyms')[$word])):
            return ['word' => config('acronyms')[$word]['uppercase'], 'category' => 'acronym'];
        endif;

        if (isset(config('religions')[$word])):
            return ['word' => config('religions')[$word], 'category' => 'religion'];
        endif;

        if (isset(config('alphabet')[$word])):
            return ['word' => config('alphabet')[$word], 'category' => 'alphabet'];
        endif;

        if (isset(config('capitalized')[$word])):
            return ['word' => config('capitalized')[$word], 'category' => 'capitalized'];
        endif;

        if (isset(config('language')[$word])):
            return ['word' => config('language')[$word], 'category' => 'language'];
        endif;

        if (in_array($word, config('made_up'))):
            return ['word' => config('made_up')[$word], 'category' => 'made_up'];
        endif;

        return ['word' => $word, 'category' => ''];
    }

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
            if($word[0] == "'" && $word[strlen($word) - 1] == "'") {
                $word = substr($word, 1, strlen($word) - 2);
                if ($word === 'n') {
                    $word = "'n";
                }
            }
            if (! isset($this->words[$word])):
                $this->words[$word] = 1;
            else:
                $this->words[$word] += 1;
            endif;
        endif;
    }

    /**
     * Retrieve the words
     *
     * @return array
     */
    public function get_words()
    {
        return $this->words;
    }

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
                $wordCloud->attributes['count'] = $wordCloud->attributes['count'] - $count;
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
