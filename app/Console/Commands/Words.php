<?php

namespace App\Console\Commands;

use App\Music\Song\Song;
use App\Words\WordCloud;
use App\Words\WordMED;
use App\Words\WordNet;
use Carbon\Carbon;
use Exception;
use Illuminate\Console\Command;
use Log;

class Words extends Command {

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'db:words
                            {--cloud : Word cloud}
                            {--sids= : Comma separated list of song ids}
                            {--aids= : Comma separated list of artist ids}
                            {--store : Save the word cloud to the database}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Runs word utilities';

    /**
     * Save to database
     *
     * @var bool
     */
    protected $store = false;

    /**
     * The word cloud
     *
     * @var array
     */
    protected $word_cloud = [];

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $options = $this->options();

        $this->store = $options['store'];

        $song_ids = null;
        if(! empty($options['sids'])):
            $song_ids = explode(',', $options['sids']);
        endif;

        $artist_ids = null;
        if(! empty($options['aids'])):
            $artist_ids = explode(',', $options['aids']);
        endif;

        if(! empty($options['cloud'])):
            $this->getWordCloud($song_ids, $artist_ids);
        endif;
    }


    /**
     * Get word by formatted case and return type of word.
     * 
     * @param string $word
     *   The word.
     */
    public function setCaseInfo($word) {
        $tmp_word = mb_strtolower($word);

        $country = $this->getCountry($tmp_word);
        if ($country):
            return $country;
        endif;

        $state = $this->getState($tmp_word);
        if ($state):
            return $state;
        endif;

        $town = $this->getTown($tmp_word);
        if ($town):
            return $town;
        endif;

        $place = $this->getPlace($tmp_word);
        if ($place):
            return $place;
        endif;

        $street = $this->getStreet($tmp_word);
        if ($street):
            return $street;
        endif;

        $month = $this->getMonth($tmp_word);
        if ($month):
            return $month;
        endif;

        $day = $this->getDay($tmp_word);
        if ($day):
            return $day;
        endif;

        $name = $this->getPersonName($tmp_word);
        if ($name):
            return $name;
        endif;

        $honorific = $this->getHonorific($tmp_word);
        if ($honorific):
            return $honorific;
        endif;

        $brand = $this->getBrand($tmp_word);
        if ($brand):
            return $brand;
        endif;

        $organisation = $this->getOrganisation($tmp_word);
        if ($organisation):
            return $organisation;
        endif;

        $acronym = $this->getAcronym($tmp_word);
        if ($acronym) {
            return $acronym;
        }

        $religion = $this->getReligion($tmp_word);
        if ($religion):
            return $religion;
        endif;

        $alphabet = $this->getAlphabet($tmp_word);
        if ($alphabet):
            return $alphabet;
        endif;

        $capitalized = $this->getCapitalized($tmp_word);
        if ($capitalized):
            return $capitalized;
        endif;

        $language = $this->getLanguage($tmp_word);
        if ($language):
            return $language;
        endif;

        if ($this->isMadeUp($tmp_word)) {
            return ['word' => $tmp_word, 'type' => 'made_up'];
        }

        return ['word' => $tmp_word, 'type' => ''];
    }

    /**
     * Get country.
     * @param string $word
     *   The word
     * @return array
     */
    private function getCountry($word) {
        if (isset(config('countries_expanded')[$word])):
            return ['word' => config('countries_expanded')[$word], 'type' => 'country'];
        else:
            return false;
        endif;
    }

    /**
     * Get state.
     * @param string $word
     *   The word
     * @return array
     */
    private function getState($word) {
        if (isset(config('states_expanded')[$word])):
            return ['word' => config('states_expanded')[$word], 'type' => 'state'];
        else:
            return false;
        endif;
    }

    /**
     * Get town.
     * @param string $word
     *   The word
     * @return array
     */
    private function getTown($word) {
        if (isset(config('towns')[$word])):
            return ['word' => config('towns')[$word], 'type' => 'town'];
        else:
            return false;
        endif;
    }

    /**
     * Get place
     * @param string $word
     *   The word
     * @return array
     */
    private function getPlace($word) {
        if (isset(config('places')[$word])):
            return ['word' => config('places')[$word], 'type' => 'place'];
        else:
            return false;
        endif;
    }

    /**
     * Get street
     * @param string $word
     *   The word
     * @return array
     */
    private function getStreet($word) {
        if (isset(config('streets')[$word])):
            return ['word' => config('streets')[$word], 'type' => 'street'];
        else:
            return false;
        endif;
    }

    /**
     * Get month
     * @param string $word
     *   The word
     * @return array
     */
    private function getMonth($word) {
        if (isset(config('months_expanded')[$word])):
            return ['word' => config('months_expanded')[$word], 'type' => 'month'];
        else:
            return false;
        endif;
    }

    /**
     * Get day
     * @param string $word
     *   The word
     * @return array
     */
    private function getDay($word) {
        if (isset(config('days_expanded')[$word])):
            return ['word' => config('days_expanded')[$word], 'type' => 'day'];
        else:
            return false;
        endif;
    }

    /**
     * Get formatted name if it is a name?
     * @param string $word
     *   The word
     * @return array
     */
    private function getPersonName($word) {
        //Ho Chi Minh, Chou En-Lai, Christina Applegate, Clarence Thomas, Santa Claus, Kurt Cobain, Leonard Cohen, John Coltrane, Perry Como, Billy Connolly, Sean Connery, Don Corleone etc
        if (isset(config('names')[$word])):
            return ['word' => config('names')[$word], 'type' => 'name'];
        else:
            return false;
        endif;
    }

    /**
     * Get honorific
     * @param string $word
     *   The word
     * @return array
     */
    private function getHonorific($word) {
        if (isset(config('honorifics')[$word])):
            return ['word' => config('honorifics')[$word], 'type' => 'honorific'];
        else:
            return false;
        endif;
    }

    /**
     * Get religion
     * @param string $word
     *   The word
     * @return array
     */
    private function getReligion($word) {
        if (isset(config('religions')[$word])):
            return ['word' => config('religions')[$word], 'type' => 'religion'];
        else:
            return false;
        endif;
    }

    /**
     * Get alphabet
     * @param string $word
     *   The word
     * @return bool
     */
    private function getAlphabet($word) {
        if (isset(config('alphabet')[$word])):
            return ['word' => config('alphabet')[$word], 'type' => 'alphabet'];
        else:
            return false;
        endif;
    }

    /**
     * Get capitalized
     * @param string $word
     *   The word
     * @return bool
     */
    private function getCapitalized($word) {
        if (isset(config('capitalized')[$word])):
            return ['word' => config('capitalized')[$word], 'type' => 'capitalized'];
        else:
            return false;
        endif;
    }

    /**
     * Is the word a country?
     * @param string $word
     *   The word
     * @return bool
     */
    private function getAcronym($word) {
        if (isset(config('acronyms')[$word])):
            return ['word' => config('acronyms')[$word]['uppercase'], 'type' => config('acronyms')[$word]['type']];
        else:
            return false;
        endif;
    }

    /**
     * Get brand
     * @param string $word
     *   The word
     * @return array
     */
    private function getBrand($word) {
        if (isset(config('brands')[$word])):
            return ['word' => config('brands')[$word], 'type' => 'brand'];
        else:
            return false;
        endif;
    }

    /**
     * Get organisation
     * @param string $word
     *   The word
     * @return array
     */
    private function getOrganisation($word) {
        if (isset(config('organisations')[$word])):
            return ['word' => config('organisations')[$word], 'type' => 'organisation'];
        else:
            return false;
        endif;
    }

    /**
     * Get language
     * @param string $word
     *   The word
     * @return array
     */
    private function getLanguage($word) {
        if (isset(config('languages')[$word])):
            return ['word' => $word, 'type' => config('languages')[$word]];
        else:
            return false;
        endif;
    }

    /**
     * Is the word made up or nonsense?
     * @param string $word
     *   The word
     * @return bool
     */
    private function isMadeUp($word) {
        return in_array($word, config('madeup'));
    }

    /**
     * Get word cloud.
     * @param array $song_ids
     *   Limit word search by song ids.
     * @param array $artist_ids
     *   Limit word search by artist ids.
     */
    private function getWordCloud($song_ids, $artist_ids)
    {
        $query = Song::select('songs.id', 'title', 'lyrics')
            ->join('artist_song', 'songs.id', '=', 'artist_song.song_id')
            ->whereNotIn('songs.id', [
                299, 404, 491, 712, 819, 908, 911, 1273, 1293, 1314, 1425, 1477, 1582, 1758, 1789, 1825, 1828, 2051, 2133, 2206, 2225, 2344, 2524, 2601, 3156, 3165, 3198, 3427, 3965, 3966, 3968, 3994, 4145, 4146, 4261, 4361, 4389, 4624, 4732, 4892, 5325, 5621, 5709, 5727, 5728, 5737, 6053, 6218, 6502, 6912, 8036, 8456, 8532, 8587, 4856, 8993, 9143, 9146, 9159, 9164, 9183, 9473, 9550, 9741, 9749, 9762,
            ])
            ->whereNotIn('artist_song.artist_id', [
                23, 84, 107, 197, 209, 211, 248, 280, 469, 510, 607, 611, 763, 802, 821, 838, 841, 846, 1317, 1453, 1516,
            ])
            ->whereNotIn('album', [
                'Turkish Groove', 'African Women', 'Bocelli Greatest Hits', 'Buena Vista Social Club', 'Everything Is Possible!',
                "Edith Piaf - 20 'French' Hit Singles",
            ])
            ->whereNotIn('lyrics', ['unavailable', 'Instrumental', 'inapplicable']);

        if ($song_ids):
            $query->whereIn('songs.id', $song_ids);
        endif;

        $lyrics = $query->get()->toArray();

        foreach ($lyrics as $song):
            try {
                $lyric = str_replace([PHP_EOL], [' '], $song['lyrics']);
                $words = explode(' ', $lyric);

                foreach ($words as $word):
                    $this->processWord($word, $song['id']);
                  endforeach;

            } catch (Exception $e) {
                Log::info($e->getMessage());
            }

        endforeach;

        if ($this->store):
            // Insert words and word info into the word_cloud table.
            $this->storeWordCloud();
        else:
            $this->logWordCloud();
        endif;

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
     * Process the word and populate the word cloud.
     * 
     * @param string $word
     *   The word.
     * @param int $id
     *   The song id.
     * 
     * @return array
     */
    private function processWord($word, $id) {
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
            // 'accattone'
            if($word[0] == "'" && $word[strlen($word) - 1] == "'") {
                $word = substr($word, 1, strlen($word) - 2);
                if ($word === 'n') {
                    $word = "'n";
                }
            }
            // Retain capitilisation for countries, months, names etc
            $word_info = $this->setCaseInfo($word);
            if (! empty($word_info)):
                if (! isset($this->word_cloud[$word_info['word']])):
                    $this->word_cloud[$word_info['word']] = [
                        'word' => $word_info['word'],
                        'category' => $word_info['type'],
                        'count' => 1,
                        'song_ids' => [$id],
                    ];
                else:
                    $this->word_cloud[$word_info['word']]['count'] += 1;
                    $this->word_cloud[$word_info['word']]['song_ids'][] = $id;
                endif;
            endif;
        endif;
    }

    /**
     * Save word cloud to the database.
     */
    private function storeWordCloud() {
        foreach($this->word_cloud as $w) {
            try {
                // Is this a real word?
                $is_word = $this->isWord($w['word']);
                if (!$is_word):
                    // Check if it is possible a plural.
                    if (substr($w['word'], -1) === 's'):
                        // Try again.
                        $is_word = $this->isWord(substr($w['word'], 0, -1));
                    endif;
                endif;
                $w['is_word'] = $is_word;

                // Save and unset song_ids for pivot table insert.
                $song_ids = array_unique($w['song_ids']);
                unset($w['song_ids']);

                $w['created_at'] = Carbon::now();
                $wordCloud = WordCloud::create($w);

                // Add word song references
                foreach($song_ids as $song_id):
                    $wordCloud->songs()->attach(['song' => $song_id]);
                endforeach;
            } catch (Exception $e) {
                Log::error($e->getMessage());
            }
        }
    }

    /**
     * Log word cloud.
     */
    private function logWordCloud() {
        ksort($this->word_cloud);
        foreach($this->word_cloud as $w => $v) {
            $is_word = $this->isWord($w);
            if (!$is_word):
                // Check if it is possible a plural.
                if (substr($w, -1) === 's'):
                    // Try again.
                    $is_word = $this->isWord(substr($w, 0, -1));
                endif;
            endif;
            $v['is_word'] = $is_word;
             // Possible check, if false if last letter is s, strip s and try again.
            $v['song_ids'] = array_unique($v['song_ids']);
            Log::info($w);
            Log::info($v);
        }
    }

}
