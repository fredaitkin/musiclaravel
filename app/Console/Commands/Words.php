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

    protected $countries = [];

    protected $states = [];

    protected $months = [];

    protected $days = [];

    /**
     * Create a new command instance.
     *
     */
    public function __construct()
    {
        $this->setCountries();
        $this->setStates();
        $this->setMonths();
        $this->setDays();
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
        $tmp_word = ucfirst(strtolower($word));

        if ($this->isCountry($tmp_word)) {
            return ['word' => $tmp_word, 'type' => 'country'];
        }

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

        if ($this->isDay($tmp_word)) {
            return ['word' => $tmp_word, 'type' => 'day'];
        }

        if ($this->isName($tmp_word)) {
            return ['word' => $tmp_word, 'type' => 'name'];
        }

        $name = $this->getNonStandardName($tmp_word);
        if ($name) {
            return ['word' => $name, 'type' => 'name'];
        }

        if ($this->isBrand($tmp_word)) {
            return ['word' => $tmp_word, 'type' => 'brand'];
        }

        if ($this->isOrganisation($tmp_word)) {
            return ['word' => $tmp_word, 'type' => 'organisation'];
        }

        $acronym = $this->getAcronym($tmp_word);
        if ($acronym) {
            return $acronym;
        }

        return ['word' => strtolower($word), 'type' => ''];
    }

    /**
     * Set country array.
     */
    private function setCountries() {
        $this->countries = config('countries');
        $this->countries[] = 'U.S';
        $this->countries[] = 'USA';
        $this->countries[] = 'America';
        $this->countries[] = 'American';
        $this->countries[] = 'Americana';
        $this->countries[] = 'Africa';
        $this->countries[] = 'Afrika';
        $this->countries[] = 'Arab';
        $this->countries[] = 'Arabs';
        $this->countries[] = 'Argentines';
        $this->countries[] = 'Asia';
        $this->countries[] = 'Asian';
        $this->countries[] = 'Asiatic';
        $this->countries[] = 'Bahama';
        $this->countries[] = 'Belgians';
        $this->countries[] = 'Bolivian';
        $this->countries[] = 'Brasilia';
        $this->countries[] = 'Brazilia';
        $this->countries[] = 'Braziliana';
        $this->countries[] = 'Britain';
        $this->countries[] = 'Britannia';
        $this->countries[] = 'British';
        $this->countries[] = 'Caribbean';
        $this->countries[] = 'Chinese';
        $this->countries[] = 'Colombian';
        $this->countries[] = 'Cuban';
        $this->countries[] = 'Dominican';
        $this->countries[] = 'Europe';
        $this->countries[] = 'European';
        $this->countries[] = 'Indians';
        $this->countries[] = 'Indochinan';
        $this->countries[] = 'Mandinka';
        $this->countries[] = 'Mexicans';
        $this->countries[] = 'Puerto';
        $this->countries[] = 'Russian';
        $this->countries[] = 'Russians';
        $this->countries[] = 'Rican';
        $this->countries[] = 'Turkish';
        $this->countries[] = 'Turks';
        $this->countries[] = 'Zulu';
        $this->countries[] = 'Zulus';
    }

    /**
     * Is the word a country?
     * @param string $word
     *   The word
     * @return bool
     */
    private function isCountry($word) {
        return in_array($word, $this->countries);
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
        $this->states[] = 'Hawaian';
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

    private function setDays() {
        $this->days = config('days');
        $this->days[] = 'Sundays';
        $this->days[] = 'Mondays';
        $this->days[] = 'Fridays';
    }

    /**
     * Is the word a country?
     * @param string $word
     *   The word
     * @return bool
     */
    private function isDay($word) {
        // May is complex, will often be a word
        return in_array($word, $this->days);
    }

    /**
     * Is the word a country?
     * @param string $word
     *   The word
     * @return bool
     */
    private function isName($word) {
        //Ho Chi Minh, Chou En-Lai, Christina Applegate, Clarence Thomas, Santa Claus, Kurt Cobain, Leonard Cohen, John Coltrane, Perry Como, Billy Connolly, Sean Connery, Don Corleone etc
        return in_array($word, config('names'));
    }

    /**
     * Is the word a country?
     * @param string $word
     *   The word
     * @return bool
     */
    private function getNonStandardName($word) {
        if (in_array($word, config('names_non_standard'))):
            return ['word' => config('names_non_standard')[$word], 'type' => 'name'];
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
    private function isHonorific($word) {
        return in_array($word, config('honorifics'));
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
     * Is the word a country?
     * @param string $word
     *   The word
     * @return bool
     */
    private function isBrand($word) {
        return in_array($word, config('brands'));
    }

    /**
     * Is the word a country?
     * @param string $word
     *   The word
     * @return bool
     */
    private function isOrganisation($word) {
        return in_array($word, config('brands'));
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
        // $common_words = ['a', 'about', 'after', 'again', 'all', 'am', at', 'the', 'where'];
        foreach($this->word_cloud as $w => $v) {
            Log::info($w);
            if (WordNet::isWord($w)):
                $v['is_word'] = true;
            else:
                // Try again.
                $v['is_word'] = WordMED::isWord($w);
             endif;
            $v['song_ids'] = array_unique($v['song_ids']);
            Log::info($v);
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
