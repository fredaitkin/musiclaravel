<?php

namespace App\Words;

use Illuminate\Database\Eloquent\Model;

class Word extends Model
{

    /**
     * Use timestamps
     *
     * @var bool
     */
    public $timestamps = true;

    /**
     * Table
     *
     * @var string
     */
    protected $table = 'word';

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
     * Is a word.
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
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    /**
     * Set word format and type.
     */
    public function setWord($word) {
        $word = mb_strtolower($word);

        if (isset(config('countries_expanded')[$word])):
            $this->word     = config('countries_expanded')[$word];
            $this->category = 'country';
            return;
        endif;

        if (isset(config('states_expanded')[$word])):
            $this->word     = config('states_expanded')[$word];
            $this->category = 'state';
            return;
        endif;

        if (isset(config('towns')[$word])):
            $this->word     = config('towns')[$word];
            $this->category = 'town';
            return;
        endif;

        if (isset(config('places')[$word])):
            $this->word     = config('places')[$word];
            $this->category = 'state';
            return;
        endif;

        if (isset(config('streets')[$word])):
            $this->word     = config('streets')[$word];
            $this->category = 'street';
            return;
        endif;

        if (isset(config('months_expanded')[$word])):
            $this->word     = config('months_expanded')[$word];
            $this->category = 'month';
            return;
        endif;

        if (isset(config('days_expanded')[$word])):
            $this->word     = config('days_expanded')[$word];
            $this->category = 'day';
            return;
        endif;

        if (isset(config('names')[$word])):
            $this->word     = config('names')[$word];
            $this->category = 'name';
            return;
        endif;

        if (isset(config('honorifics')[$word])):
            $this->word     = config('honorifics')[$word];
            $this->category = 'honorific';
            return;
        endif;

        if (isset(config('brands')[$word])):
            $this->word     = config('brands')[$word];
            $this->category = 'brand';
            return;
        endif;

        if (isset(config('organisations')[$word])):
            $this->word     = config('organisations')[$word];
            $this->category = 'organisation';
            return;
        endif;

        if (isset(config('acronyms')[$word])):
            $this->word     = config('acronyms')[$word];
            $this->category = 'acronym';
            return;
        endif;

        if (isset(config('religions')[$word])):
            $this->word     = config('religions')[$word];
            $this->category = 'religion';
            return;
        endif;

        if (isset(config('alphabet')[$word])):
            $this->word     = config('alphabet')[$word];
            $this->category = 'alphabet';
            return;
        endif;

        if (isset(config('capitalized')[$word])):
            $this->word     = config('capitalized')[$word];
            $this->category = 'capitalized';
            return;
        endif;

        if (isset(config('language')[$word])):
            $this->word     = config('language')[$word];
            $this->category = 'language';
            return;
        endif;

        if (in_array($word, config('madeup'))):
            $this->word     = $word;
            $this->category = 'made_up';
            return;
        endif;

        $this->word = $word;
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

    /**
     * Process the word.
     *
     * @param string $word
     *   The word
     * @param string $action
     *   Add or remove
     */
    public function process($word, $action) {
        // Clean up text.
        $chars_to_replace = [',', '.', '"', ' ', '!', '?', '[', ']', '(', ')', '{', '}', '&', "''", '*', ';', '…', '~'];
        $word = str_replace(
            $chars_to_replace,
            array_fill(0, count($chars_to_replace), ''),
            $word
        );
        // Replace ticks and curly quotes..
        $word = str_replace(['`', '‘', '’'], ["'", "'", "'"], $word);
        // Strip certain characters from the start and the end of words.
        $chars_to_trim = " :-/\0\t\n\x0B\r";
        $word = trim($word, $chars_to_trim);
        if (! empty($word) && ! preg_match('/^[-]+$/', $word)):
            if($word[0] == "'" && $word[strlen($word) - 1] == "'") {
                $word = substr($word, 1, strlen($word) - 2);
                if ($word === 'n') {
                    $word = "'n";
                }
            }
            // Retain capitilisation for countries, months, names etc
            $this->setWord($word);
            $word_cloud = WordCloud::firstWhere('word', $this->word);
            if ($word_cloud):
                if ($action == 'add'):
                    $word_cloud->count += 1;
                else:
                    $word_cloud->count -= 1;
                    // @todo if 0 contemplate removing;
                endif;
                $word_cloud->save();
            else:
                if ($action == 'add'):
                    WordCloud::create([
                        'word'      => $this->word,
                        'category'  => $this->category,
                        'count'     => 1,
                    ]);
                endif;
            endif;
        endif;
    }

}
