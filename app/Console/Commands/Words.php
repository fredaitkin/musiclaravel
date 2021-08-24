<?php

namespace App\Console\Commands;

use App\Music\Song\Song;
use App\Words\WordMED;
use App\Words\WordNet;
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
                            {--aids= : Comma separated list of artist ids}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Runs word utilities';

    /**
     * The word cloud
     *
     * @var array
     */
    protected $word_cloud = [];

    protected $states = [];

    protected $months = [];

    /**
     * Create a new command instance.
     *
     */
    public function __construct()
    {
        $this->setStates();
        $this->setMonths();
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $options = $this->options();

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
        $tmp_word = strtolower($word);

        $country = $this->getCountry($tmp_word);
        if ($country):
            return $country;
        endif;

        if ($this->isState($tmp_word)) {
            return ['word' => $tmp_word, 'type' => 'country'];
        }

        if ($this->isTown($tmp_word)) {
            return ['word' => $tmp_word, 'type' => 'town'];
        }

        if ($this->isPlace($tmp_word)) {
            return ['word' => $tmp_word, 'type' => 'place'];
        }

        if ($this->isStreet($tmp_word)) {
            return ['word' => $tmp_word, 'type' => 'street'];
        }

        if ($this->isMonth($tmp_word)) {
            return ['word' => $tmp_word, 'type' => 'month'];
        }

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

        if ($this->isOrganisation($tmp_word)) {
            return ['word' => $tmp_word, 'type' => 'organisation'];
        }

        $acronym = $this->getAcronym($tmp_word);
        if ($acronym) {
            return $acronym;
        }

        $religion = $this->getReligion($tmp_word);
        if ($religion):
            return $religion;
        endif;

        if ($this->isCapitalized($tmp_word)) {
            return ['word' => $tmp_word, 'type' => 'miscellaneous'];
        }

        // Ik ben droevig Dutch
        // sol invictus Latin
        // jacamo
        // Jai Guru Deva
        // Day o umba day o mambu ji ay o
        // Konnichiwa   Senso hant-ai
        // pon de replay Bajan Creole play it again
        // Chuva! Chuvinha! Vem, vem, vem ca, rapaz! Portugese

        if ($this->isFrench($tmp_word)) {
            return ['word' => strtolower($tmp_word), 'type' => 'French'];
        }

        if ($this->isSpanish($tmp_word)) {
            return ['word' => strtolower($tmp_word), 'type' => 'Italian'];
        }

        if ($this->isItalian($tmp_word)) {
            return ['word' => strtolower($tmp_word), 'type' => 'Italian'];
        }

        if ($this->isGerman($tmp_word)) {
            return ['word' => strtolower($tmp_word), 'type' => 'German'];
        }

        if ($this->isMadeUp($tmp_word)) {
            return ['word' => strtolower($tmp_word), 'type' => 'made_up'];
        }

        return ['word' => $word, 'type' => ''];
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
     * Set state array
     */
    public function setStates() {
        $this->states = config('states');
        $this->states[] = 'Alberta';
        $this->states[] = 'Bamee';
        $this->states[] = 'Cali';
        $this->states[] = 'Californication';
        $this->states[] = 'Georgian';
        $this->states[] = 'Hawaiian';
        $this->states[] = 'Jersey';
        $this->states[] = 'Rhode';
        $this->states[] = 'Virginny';
    }

    /**
     * Is the word a state?
     * @param string $word
     *   The word
     * @return bool
     */
    private function isState($word) {
        return in_array($word, $this->states);
    }

    /**
     * Is the word a country?
     * @param string $word
     *   The word
     * @return bool
     */
    private function isTown($word) {
        return in_array($word, config('towns'));
    }

    /**
     * Is the word a country?
     * @param string $word
     *   The word
     * @return bool
     */
    private function isPlace($word) {
        return in_array($word, config('places'));
    }

    /**
     * Is the word a country?
     * @param string $word
     *   The word
     * @return bool
     */
    private function isStreet($word) {
        return in_array($word, config('streets'));
    }

    /**
     * Is the word a country?
     * @param string $word
     *   The word
     * @return bool
     */
    private function setMonths() {
        $this->months = config('months');
        $this->months[] = 'Junes';
    }

    /**
     * Is the word a country?
     * @param string $word
     *   The word
     * @return bool
     */
    private function isMonth($word) {
        // May is complex, will often be a word
        return in_array($word, $this->months);
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
     * Is the word a country?
     * @param string $word
     *   The word
     * @return bool
     */
    private function getAcronym($word) {
        $tmp_word = strtoupper($word);
        if (isset(config('acronyms')[$tmp_word])):
            $acronym = config('acronyms')[$tmp_word];
            return ['word' => $tmp_word, 'type' => $acronym['type']];
        else:
            return false;
        endif;
    }

    /**
     * A miscellaneous capitalized word
     * @param string $word
     *   The word
     * @return bool
     */
    private function isCapitalized($word) {
        // May is complex, will often be a word
        return in_array($word, config('capitalized'));
    }

    /**
     * Get formatted brand if it is a brand?
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
     * Is the word an organisation?
     * @param string $word
     *   The word
     * @return bool
     */
    private function isOrganisation($word) {
        return in_array($word, config('organisations'));
    }

    /**
     * Is the word French?
     * @param string $word
     *   The word
     * @return bool
     */
    private function isFrench($word) {
        return in_array($word, config('french'));
    }

    /**
     * Is the word Spanish?
     * @param string $word
     *   The word
     * @return bool
     */
    private function isSpanish($word) {
        return in_array($word, config('spanish'));
    }

    /**
     * Is the word Italian?
     * @param string $word
     *   The word
     * @return bool
     */
    private function isItalian($word) {
        return in_array($word, config('italian'));
    }

    /**
     * Is the word German?
     * @param string $word
     *   The word
     * @return bool
     */
    private function isGerman($word) {
        return in_array($word, config('german'));
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
        ksort($this->word_cloud);

        // TODO don't include song_ids from common words
        // $common_words = ['a', 'about', 'after', 'again', 'all', 'am', 'an', 'and', 'are', 'around', 'as', 'at', 'be', 'been', 'but', 'by', 'can', 'do', 'for', 'from', 'get', 'got', 'gotta', 'had', 'has', 'have', 'i', 'if', 'in', 'into', 'is', 'it', 'its', 'just', 'my', 'not', 'of', 'oh', 'on', 'or', 'so', 'that', 'the', 'there', 'these', this', 'those', 'through', 'to', 'too', 'was', 'were', 'what', 'when', 'where', 'will', 'with', 'would'];
        foreach($this->word_cloud as $w => $v) {
            Log::info($w);
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
            Log::info($v);
        }
    }

    private function isWord($w) {
        if (WordNet::isWord($w)):
            return true;
        else:
            return WordMED::isWord($w);
         endif;
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
        // Ignore non-Latin words.
        if (preg_match('/^\p{Latin}+$/', $word)): 
            // Retain capitilisation for countries, months, names etc
            $word_info = $this->setCaseInfo($word);
            if (! empty($word_info)):
                if (! isset($this->word_cloud[$word_info['word']])):
                    $this->word_cloud[$word_info['word']] = ['type' => $word_info['type'], 'count' => 1, 'song_ids' => [$id]];
                else:
                    $this->word_cloud[$word_info['word']]['count'] += 1;
                    $this->word_cloud[$word_info['word']]['song_ids'][] = $id;
                endif;
            endif;
        endif;
    }

}
