<?php

namespace App\Console\Commands;

use App\Music\Song\Song;
use App\Words\Word;
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

    protected $countries;

    protected $places;

    protected $months = [];

    protected $names = [];

    protected $brands = [];

    /**
     * Create a new command instance.
     *
     */
    public function __construct()
    {
        $this->setCountries();
        $this->setPlaces();
        $this->setMonths();
        $this->setNames();
        $this->setBrands();
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

    public function setCountries() {
        try {
            $this->countries = getCountryNames();
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
            $this->countries[] = 'Belgians';
            $this->countries[] = 'Bolivia'; // API returns this as "Bolivia (Plurinational State of)"
            $this->countries[] = 'Bolivian';
            $this->countries[] = 'Britain';
            $this->countries[] = 'Britannia';
            $this->countries[] = 'British';
            $this->countries[] = 'Chinese';
            $this->countries[] = 'Colombian';
            $this->countries[] = 'Cuban';
            $this->countries[] = 'Dominican';
            $this->countries[] = 'Europe';
            $this->countries[] = 'Russian';
            $this->countries[] = 'Zulu';
            $this->countries[] = 'Zulus';
            unset($this->countries['Multipe']);
            unset($this->countries['Unknown']);
        } catch (Exception $e) {
            Log::error("Redis is not connected");
            $this->countries = [];
        }

    }

    private function isCountry($word) {
        return in_array($word, $this->countries);
    }

    private function setPlaces() {
        //States // TOWNs  // Cities
        // Cannot handle West Memphis or New York City Great Britain, Lake Charles, Los Angeles, Buenos Aires, Tel Aviv, Baton Rouge, Las Vegas, Dien Bien Phu, Lower East side, Carson City, La Cienega. Sacre Coeur, Coney Island
        $this->places = [
            'Aberdeen',
            'Aberdine',
            'Acapulco',
            'Accrington',
            'Agadir',
            'Aires',
            'Alabama',
            'Alaska',
            'Alberta',
            'Albuquerque',
            'Algiers',
            'Allentown',
            'Amazon',
            'Amazonians',
            'Amsterdam',
            'Anaheim',
            'Andalusia',
            'Angeles',
            'Annan',
            'Antarctic',
            'Appalachia',
            'Appel',
            'Argonauts',
            'Arizona',
            'Arkansas',
            'Athens',
            'Atlanta',
            'Atlantic',
            'Atlantis',
            'Austin', // also a name
            'Avalon',
            'Aviv',
            'Azul',
            'Babylon',
            'Baghdad',
            'Bahama',
            'Bali',
            'Balie',
            'Bally',
            'Baltic',
            'Baltimore',
            'Bamee',
            'Bangkok',
            'Barcelona',
            'Barrington',
            'Barstow',
            'Baton',
            'Beale',
            'Beaumont',
            'Beijing',
            'Beirut',
            'Belfast',
            'Belmont',
            'Bergere',
            'Berlin',
            'Bethlehem',
            'Beverly', // also a name
            'Bien',
            'Birmingham',
            'Bissau',
            'Bombay',
            'Bondi',
            'Boston',
            'Brasilia',
            'Brazilia',
            'Braziliana',
            'Brighton',
            'Brixton',
            'Brooklyn',
            'Bronx',
            'Brussels',
            'Buenos',
            'Calgary',
            'Cali',
            'California',
            'Californication',
            'Campbell', // also a name
            'Canaan',
            'Cannes',
            'Caribbean',
            'Carlisle',
            'Carson', // also a name
            'Cebu',
            'Cerro',
            'Chattanooga',
            'Chelsea', // also a name
            'Chernobyl',
            'Chesapeake',
            'Chicago',
            'Chicano',
            'Cicero', // also a name
            'Cienega',
            'Clapham',
            'Cleveland',
            'Clinton', // also name
            'Coeur',
            'Collingwood',
            'Colorado',
            'Coney',
            'Constantinople',
            'Copmanhurst',
            'Corning',
            'Costa',
            'Cronkite',
            'Cuyahoga',
            'Danforth',
            'Detroit',
            'Dharamsala',
            'Diagonalia',
            'Diana',
            'Diane',
            'Diego',
            'Dien',
            'Disneyland',
            'Disneyworld',
            'Dohini',
            'Ebudae',
            'Eden',
            'Fitzroy',
            'Galveston',
            'Galilee',
            'Greenpoint',
            'Greenville',
            'Guantanamo',
            'Hobart',
            'Hoovre',
            'Huntington',
            'Istanbul',
            'Jackson', // Also a name 
            'Khartoum',
            'L.A',
            'Laguna',
            'Los',
            'Louvre',
            'Macquarie',
            'Madison',
            'Malibu',
            'Malvinas',
            'Melbourne',
            'Memphis',
            'Michigan',
            'Mississippi',
            'Montague',
            'Moscow',
            'Napoli',
            'Olomouc',
            'Orleans',
            'Oxford',
            'PA',
            'Palau',
            'Perth',
            'Phu',
            'Reno',
            'Rockville',
            'Rosedale',
            'Rouge',
            'Sacre',
            'Siberia',
            'Slidell',
            'Soho',
            'Sydney',
            'Taroudant',
            'Tel',
            'Tiree',
            'Trenton',
            'Tucson',
            'Winnemucca',
            'Zelda',
        ];
    }

    private function isPlace($word) {
        return in_array($word, $this->places);
    }

    private function setMonths() {
        $this->months = [
            'January',
            'February',
            'March',
            'April',
            'May',
            'June',
            'July',
            'August',
            'September',
            'October',
            'November',
            'December',
        ];
    }

    private function isMonth($word) {
        // May is complex, will often be a word
        return in_array($word, $this->months);
    }

    private function setNames() {
        //Ho Chi Minh, Chou En-Lai, Christina Applegate, Clarence Thomas, Santa Claus, Kurt Cobain, Leonard Cohen, John Coltrane, Perry Como, Billy Connolly, Sean Connery, Don Corleone etc
        $this->names = [
            'Aaliyah',
            'Abdul',
            'Abe',
            'Abel',
            'Abigail',
            'Abraham',
            'Adam',
            'Agamemnon',
            'Ajax',
            'Al',
            'Alan',
            'Albert',
            'Aldous',
            'Alec',
            'Alejandro',
            'Alex',
            'Alexander',
            'Alexandra',
            'Alfie',
            'Ali',
            'Alice',
            'Alison',
            'Allan',
            'Alvin',
            'Allison',
            'Alma',
            'Amadeus',
            'Amanda',
            'Amy',
            'Andre',
            'Andy',
            'Ana',
            'Angelica',
            'Ann',
            'Anna',
            'Annabelle',
            'Anne',
            'Annie',
            'Anthony',
            'Anton',
            'Antone',
            'Antonio',
            'Antony',
            'Apepig',
            'Apollo', // also a place
            'Aristophanes',
            'Arthur',
            'Astaire',
            'Astrid',
            'Athena',
            'Ayhern',
            'Babar',
            'Bacall',
            'Bambaataa',
            'Bandini',
            'Barbara',
            'Barbarella',
            'Barratt',
            'Barry',
            'Bart',
            'Beethoven',
            'Behan',
            'Belinda',
            'Belisha',
            'Ben',
            'Benji',
            'Benny',
            'Berkeley',
            'Bernie',
            'Bert',
            'Bethany',
            'Bette',
            'Betties',
            'Betty',
            'Beyonce',
            'Billie',
            'Billy',
            'Bizkit',
            'Blassie',
            'Bledsoe',
            'Blumen',
            'Bo',
            'Bobby',
            'Bojangles',
            'Boris',
            'Bowie',
            'Brad',
            'Brando',
            'Brandon',
            'Brenda',
            'Brendan',
            'Bretty',
            'Brezhnev',
            'Brian',
            'Bruce',
            'Brucie',
            'Brummel',
            'Brynner',
            'Bugsy',
            'Bunyan',
            'Burdett',
            'Burke',
            'Busby',
            'Caesar',
            'Cain',
            'Caligula',
            'Callas',
            'Cameron',
            'Camilla',
            'Camille',
            'Capone',
            'Carla',
            'Carol',
            'Carolina',  // also a place
            'Caroline',
            'Cary',
            'Casey',
            'Cass',
            'Castro',
            'Cecilia',
            'Chandra',
            'Charles',
            'Charlie',
            'Charlotte',
            'Charo',
            'Charybdis',
            'Che',  // also just a sound
            'Cheeba',
            'Cheney',
            'Chi',
            'Chino',
            'Chou',
            'Chris',
            'Christi',
            'Christina',
            'Christopher',
            'Churchill',
            'Clair',
            'Clarence',
            'Claude',
            'Claus',
            'Cleese',
            'Cleopatra',
            'Clyde',
            'Cobain',
            'Cohen',
            'Cole',
            'Coltrane',
            'Columbine',
            'Como',
            'Confucius',
            'Connolly',
            'Connery',
            'Copernicus',
            'Corleone',
            'Coronel',
            'Cosell',
            'Courtney',
            'Cronkite',
            'Crowter',
            'Cyrano',
            'D', // Bobby D, D from RunDMC
            'Dakota', // also a state
            'Dan',
            'Daniel',
            'Daniella',
            'Danny',
            'Darbanville',
            'Darlis',
            'Darryl',
            'Darwin',
            'Dave',
            'David',
            'Davis',
            'Davy',
            'Dean',
            'Debra',
            'Degas',
            'Deitrich',
            'Delilah',
            'Denise',
            'Dennis',
            'Denny',
            'Desmond',
            'Deva',
            'Dexter',
            'Dickins',
            'Dimaggio',
            'Dinah',
            'Disney',
            'DMC',
            'Don',
            'Dooler',
            'Dorian',
            'Doris',
            'Douglas',
            'Earle',
            'Edison',
            'Einstein',
            'Elvis',
            'Erin',
            'Fairbanks',
            'Fred',
            'Fuegos',
            'Galileo',
            'Garbo',
            'Gavrilo',
            'Geronimo',
            'Giuseppe',
            'God',
            'Goodman',
            'Grace',
            'Greta',
            'Guevara',
            'Gwar',
            'Hayworth',
            'Hercules',
            'Hoople',
            'Horner',
            'Hugh',
            'Jack',
            'Joe',
            'John',
            'Johnson',
            'Juanita',
            'Kafka',
            'Katherine',
            'Katie',
            'Kaufman',
            'Kelly',
            'Kevin',
            'Kurt',
            'Lana',
            'Lauren',
            'Laver',
            'Leonard',
            'Lil',
            'Louis',
            'Lucy',
            'Luther',
            'Macey',
            'Magill',
            'Matt',
            'Maria',
            'Marilyn',
            'Martin',
            'Mathilde',
            'Milena',
            'Mohamed',
            'Monroe',
            'Moses',
            'Mott',
            'Muhammad',
            'Norman',
            'Perry',
            'Peter',
            'Pierrot',
            'Popeye',
            'Princip',
            'Rachaminoff',
            'Rayvon',
            'Richard',
            'Rita',
            'Rod',
            'Rogers',
            'Ross',
            'Roxie',
            'Sadie',
            'Sally',
            'Samantha',
            'Samson',
            'Scylla',
            'Sean',
            'Seavers',
            'Shaggy',
            'Sophia',
            'Sylvia',
            'Teresa',
            'Tartanella',
            'Thatcher',
            'Thomas',
            'Wagner',
            'Whitfield',
            'William',
        ];
    }

    private function isName($word) {
        return in_array($word, $this->names);
    }

    private function setBrands() {
        // Things, No Doz, Cracker Jack
        $this->brands = [
            'ABC',
            'Adidas',
            'Amtracks',
            'Armalite',
            'Armani',
            'Bacardi',
            'Baileys',
            'Batego',
            'BBC',
            'Beatlemania',
            'Benz',
            'Benzie',
            'Blackstrap',
            'BLS',
            'Buicks',
            'Bundeburg',
            'Cadi',
            'Cadillac',
            'Cadillacs',
            'Cartier',
            'Casio',
            'CB', // thing etc
            'CBS',
            'CD',
            'CEO',
            'Chablis', // also a place
            'Chevrolet',
            'Chevy',
            'Chrysler',
            'CNN',
            'Coca',
            'Coke',
            'Corona',
            'Corvette',
            'Crayola',
            'Dacron',
            'Danneman',
            'Darjeeling',
            'Datsuns',
            'DNA',
            'Dolce',
            'Dorado',
            'Doz',
            'Fendi',
            'Gabbana',
            'Guici',
            'Hoover',
            'JFK',
            'Karan',
            'Khus',
            'Mercedes',
            'Moschino',
            'MTV',
            'NBC',
            'NRA',
            'Reebok',
            // 'TAB',
            'Vanetto',
            'VP',
            'Woody',
        ];
    }

    private function isBrand($word) {
        return in_array($word, $this->brands);
    }

    /**
     * Get word cloud.
     *
     */
    protected function getWordCloud($song_ids, $artist_ids)
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
        foreach($this->word_cloud as $w => $v) {
            if (!Word::isWord($w)) {
                Log::info($w);
                Log::info($v);
            }

        }
    }

    /**
     * Get word cloud.
     *
     */
    public function processWord($word, $id) {
        // Ignore non-Latin words.
        if (preg_match('/^\p{Latin}+$/', $word)):
            // Retain capitilisation for countries, months, names etc
            $word = $this->setCase($word);
            if (! empty($word)):
                if (!isset($this->word_cloud[$word])):
                    $this->word_cloud[$word] = [];
                endif;
                if (is_array($this->word_cloud[$word])):
                    if (count($this->word_cloud[$word]) == 20):
                        $this->word_cloud[$word] = 21;
                    else:
                        $this->word_cloud[$word][] = $id;
                    endif;
                else:
                    $this->word_cloud[$word] += 1;
                endif;
            endif;
        endif;
    }

    public function setCase($word) {
        $tmp_word = ucfirst(strtolower($word));

        if ($this->isCountry($tmp_word)) {
            return $tmp_word;
        }

        if ($this->isPlace($tmp_word)) {
            return $tmp_word;
        }

        if ($this->isMonth($tmp_word)) {
            return $tmp_word;
        }

        if ($this->isName($tmp_word)) {
            return $tmp_word;
        }

        if ($this->isBrand($tmp_word)) {
            return $tmp_word;
        }

        return strtolower($word);
    }
}
