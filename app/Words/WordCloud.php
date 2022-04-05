<?php

namespace App\Words;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Kyslik\ColumnSortable\Sortable;
use Watson\Rememberable\Rememberable;

class WordCloud extends Model
{

    use Rememberable;

    use Sortable;

    /**
     * Use timestamps
     *
     * @var bool
     */
    public $timestamps = true;

    protected $table = 'word_cloud';

    /**
     * The primary key for the model.
     *
     * @var integer
     */
    protected $id;

    /**
     * The word.
     *
     * @var string
     */
    protected $word;

    /**
     * Number of times the word appears in lyrics.
     *
     * @var integer
     */
    protected $count;

    /**
     * Recognized as word by the two dictionary databases.
     *
     * @var bool
     */
    protected $is_word;

    /**
     * Category.
     *
     * @var string
     */
    protected $category;

    /**
     * The id of the word this word is the variant of.
     *
     * @var integer
     */
    protected $variant_of;

    /**
     * Created date.
     *
     * @var datetime
     */
    protected $created_at;

    /**
     * Updated date.
     *
     * @var datetime
     */
    protected $updated_at;

    /**
     * Sortable columns
     *
     * @var array
     */
    protected $sortable = [
        'word',
        'category',
        'count',
    ];

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    /**
     * Song words
     *
     * @var array
     */
    protected $words = [];

    /**
     * Word cloud categories
     */
    public function categories()
    {
        // Specify table name as it does not follow the default laravel naming convention
        return $this->belongsToMany('App\Category\Category', 'word_category');
    }

    /**
     * Word cloud categories for display
     */
    public function getCategoryDisplayAttribute()
    {
        $categories = [];
        foreach ($this->categories as $category) {
            $categories[] = $category->category;
        }
        return implode(',', $categories);
    }

    /**
     * Word cloud category ids
     */
    public function getCategoryIdsAttribute()
    {
        $categories = [];
        foreach ($this->categories as $category) {
            $categories[] = $category->id;
        }
        return implode(',', $categories);
    }

    /**
     * Get the category ids as an array
     * @return string
     */
    public function getCategoryArrayAttribute()
    {
        $categories = [];
        foreach ($this->categories as $category) {
            $categories[] = $category->id;
        }
        return $categories;
    }

    /**
     * Word cloud songs
     */
    public function songs()
    {
        return $this->belongsToMany('App\Music\Song\Song');
    }

    /**
     * Get variant as word
     *
     * @return string
     */
    public function getVariantAttribute()
    {
        $word = json_decode($this);
        $variant = WordCloud::select('word')->where('id', $word->variant_of)->get()->toArray();
        return $variant[0]['word'] ?? '';
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
     * Remove words from word cloud.
     *
     * @param int $id
     *   The song id.
     */
    private function removeWords($id) {
        foreach($this->words as $word => $count):
            $wordCloud = self::whereRaw('LOWER(word) = ?', $word)->first();
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
            $wordCloud = self::whereRaw('LOWER(word) = ?', mb_strtolower($word))->first();
            if($wordCloud):
                $wordCloud->attributes['count'] = $wordCloud->attributes['count'] + $count;
            else:
                $wordCloud = self::create([
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
