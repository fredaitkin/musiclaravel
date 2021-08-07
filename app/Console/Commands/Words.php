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

    protected $days = [];

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
        $this->setDays();
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

// MISC I, Im
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
            'Beale', // street
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
            'Broadway',
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
            'Cauldrum',
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
            'Dublin',
            'Dunblane',
            'Dundee',
            'Ebudae',
            'Eden',
            'Elaine', // bar in NYC as building/establishme!
            'Euston',
            'Finnoe',
            'Fitzroy',
            'FLA',
            'Francisco',
            'Frisco',
            'Frenchmen',
            'Frontenac',
            'Fulsom',
            'Galveston',
            'Galilee',
            'Geneva',
            'Ginza',
            'Glenaveigh',
            'Gower',
            'Graceland', // like Disneyland, not really a place?
            'Grasmeres',
            'Greeks',
            'Greenpoint',
            'Greenville',
            'Guantanamo',
            'Guernica',
            'Haight', //street
            'Hampstead',
            'Hawaian',
            'Hindu',
            'Hobart',
            'Hong',
            'Hoovre',
            'Humberside',
            'Huntington',
            'Hyannisport',
            'Inisfree',
            'Istanbul',
            'Itchycoo',
            'Jackson', // Also a name 
            'Jose', // Also a name 
            'Khartoum',
            'Khe',
            'Khyber',
            'Kilda',
            'Kilimanjaro',
            'Kilronan',
            'Kintyre',
            'Kokomo',
            'Kong',
            'Kowloon',
            'Kush',
            'L.A',
            'Laguna',
            'Lahaina',
            'Largo',
            'Las',
            'Leith',
            'Lethbridge',
            'Liula',
            'Liverpool',
            'Los',
            'Louvre',
            'Macquarie',
            'Madison',
            'Malibu',
            'Malvinas',
            'Mariana',
            'Marrickville',
            'Martinque',
            'Melbourne',
            'Memphis',
            'Mersey',
            'Metuchen',
            'Michigan',
            'Mississippi',
            'Monica', // Santa
            'Monserrat',
            'Montague',
            'Montego',
            'Montgomery',
            'Montreux',
            'Morningside',
            'Moscow',
            'Mulholland', //drive
            'Napoli',
            'Narrabri',
            'Ngall',
            'Niagra',
            'NYC',
            'Oaktown',
            'Olomouc',
            'Opelousas',
            'Orleans',
            'Oxford',
            'PA',
            'Palau',
            'Parris',
            'Perth',
            'Peyton',
            'Philly',
            'Phu',
            'Piha',
            'Pineola',
            'Pomona',
            'Ponchartrain',
            'Powis', // street
            'Provincetown',
            'Ramblas',
            'Reno',
            'Rhode',
            'Rimini',
            'Rincon',
            'Rockaway',
            'Rockville',
            'Rollox',
            'Rosedale',
            'Rotherhide',
            'Rouge',
            'Rubicon',
            'Sacre',
            'Sanh',
            'Scarborough',
            'Serengeti',
            'Shenandoah',
            'Shinrone',
            'Siberia',
            'Slidell',
            'Sloane', // street
            'Smithfield',
            'Soho',
            'Sorrento',
            'St',
            'Staten',
            'Subiaco',
            'Suez',
            'Sydney',
            'Taroudant',
            'Tel',
            'Tetons',
            'Thames',
            'Tiananmen', // "street"
            'Tipperary',
            'Tiree',
            'Transylvania',
            'Trenton',
            'Tropez',
            'Tucson',
            'Tyne',
            'Vegas',
            'Ventura',
            'Virginny', // state
            'Walla',
            'Watertown',
            'Westwood',
            'Whitehouse',
            'Winnemucca',
            'Xenia',
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
            'Junes',
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

    private function setDays() {
        $this->days = [
            'Sunday',
            'Sundays',
            'Monday',
            'Mondays',
            'Tuesday',
            'Wednesday',
            'Thursday',
            'Friday',
            'Fridays',
            'Saturday',
        ];
    }

    private function isDay($word) {
        // May is complex, will often be a word
        return in_array($word, $this->days);
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
            'Achad',
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
            'Allison',
            'Alma',
            'Alvin',
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
            // 'Beck', keep as word
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
            'Britney',
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
            'Chung',
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
            'Copelia',
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
            'Dooley',
            'Dorian',
            'Doris',
            'Douglas',
            'Dozier',
            'Dr', // generic person
            'Dre',
            'Dredd',
            'Dwight',
            'Dwyer',
            'Earle',
            'Easton',
            'Ebert',
            'Eddie',
            'Edison',
            'Edith',
            'Eileen',
            'Einstein',
            'Eleanor',
            'Eli',
            'Elise',
            'Ella',
            'Elmo',
            'Elsa',
            'Elton',
            'Elvis',
            'Emily',
            'Eminem',
            'Emma',
            'Emmalene',
            'Emmeline',
            'Emmanuel',
            'Enid',
            'Eric',
            'Erin',
            'Evan',
            'Evans',
            'Evie',
            'Ezekial',
            'Fairbanks',
            'Fergie',
            'Ferdinand',
            'Fernando',
            'Flo',
            'Flynn',
            'Francis',
            'Frankie',
            'Franklin',
            'Frater', // generic person
            'Fred',
            'Fuegos',
            'Funderburk',
            'Gable',
            'Galileo',
            'Garbo',
            'Garland',
            'Gavrilo',
            'George',
            'Georgie',
            'Georgy',
            'Geronimo',
            'Gina',
            'Giuliani',
            'Giuseppe',
            'God',
            'Gonne',
            'Goode',
            'Goodman',
            'Gordon',
            'Grace',
            'Gregg',
            'Gregor',
            'Greta',
            'Guevara',
            'Gus',
            'Gwar',
            'Hailey',
            'Hailie',
            'Hannah',
            'Han',
            'Hans',
            'Hanson',
            'Harold',
            'Harriet',
            'Harry',
            'Hawtrey',
            'Hayley',
            'Hayworth',
            'Heathcliff',
            'Hendrix',
            'Hercules',
            'Homer',
            'Hoople',
            'Horner',
            'Hugh',
            'Hyde',
            'Iggy',
            'Igor',
            'Ivor',
            'Jack',
            'Jackie',
            'Jacky',
            'Jacqueline',
            'Jagger',
            'Jainy',
            'Jamie',
            'Jane',
            'Janie',
            'Janis',
            'Jay',
            'Jeannie',
            'Jekyll',
            'Jedi',
            'Jenny',
            'Jeru',
            'Jesse',
            'Jessie',
            'Jigga',
            'Jill',
            'Jim',
            'Jimi',
            'Jimie',
            'Jo',
            'Joan',
            'Joanie',
            'Joanna',
            'Jocelyn',
            'Jock',
            'Jodeci',
            'Joe',
            'Joey',
            'Johanna',
            'John',
            'Johnny',
            'Johnson',
            'Jolene',
            'Jonas',
            'Jonny',
            'Joplin',
            'Jordan',
            'Josephine',
            'Juan',
            'Juanita',
            'Judy',
            'Julia',
            'Juliana',
            'Julianus',
            'Julie',
            'Juliet',
            'Juliets',
            'Julio',
            'Kafka',
            'Kate',
            'Katherine',
            'Kathleen',
            'Kathryn',
            'Katie',
            'Katy',
            'Kaufman',
            'Kay',
            'Kaye',
            'Kelly',
            'Kellys',
            'Kevin',
            'Khan',
            'Kim',
            'Khrushchev',
            'Kool',
            'Kris',
            'Kross',
            'Kublai',
            'Kunst',
            'Kurt',
            'Kurtis',
            'Kylie',
            'Lamont',
            'Lana',
            'Landy',
            'Laura',
            'Lauren',
            'Laver',
            'Lawrence',
            'Leann',
            'Lejos',
            'Lenny',
            'Leonard',
            'Leoni',
            'Leonid',
            'Leroy',
            'Lesley',
            'Lester',
            'Levon',
            'Liddy',
            'Lil',
            'Lili',
            'Lisa',
            'Liz',
            'Lizzy',
            'Lois',
            'Lola',
            'Loretta',
            'Lou',
            'Louie',
            'Louis',
            'Louise',
            'Lovett',
            'Lucy',
            'Ludacris',
            'Luka',
            'Luther',
            'Lyle',
            'Lysistrata',
            'Macey',
            'Maclennane',
            'Mae',
            'Magdalene',
            'Maggie',
            'Magill',
            'Magnani',
            'Malcolm',
            'Mandy',
            'Manuel',
            'Mao',
            'Margaret',
            'Marguerita',
            'Maria',
            'Mariah',
            'Marianne',
            'Marie',
            'Marilyn',
            'Marlene',
            'Marlon',
            'Marsha',
            'Martha',
            'Martin',
            'Marvin',
            'Mary',
            'Marys',
            'Mase',
            'Mathers',
            'Mathilde',
            'Matt',
            'Maud',
            'Maurice',
            'McCoy',
            'McGee',
            'McGreer',
            'McGuinn',
            'McQuire',
            'McHugh',
            'McQueen',
            'McKenzie',
            'McVeigh',
            'Medusa',
            'Meyer',
            'Michelle',
            'Michelles',
            'Mick',
            'Mikey',
            'Milena',
            'Minogue',
            'Miroslaw',
            'Mitchy',
            'Mohamed',
            'Mona',
            'Monroe',
            'Moses',
            'Mott',
            'Mozarts',
            'Mr',
            'Muhammad',
            'Muriel',
            'Nas',
            'Natalie',
            'Nate',
            'Neil',
            'Nelly',
            'Nelson',
            'Nikita',
            'Nino',
            'Norman',
            'Nosaj',
            'Obie',
            'Ogleby',
            'Ollie',
            'Ono',
            'Ophelia',
            'Oran',
            'Oscar',
            'Ozilline',
            'Pablo',
            'Pam',
            'Pasolini',
            'Peltier',
            'Penny',
            'Perry',
            'Pete',
            'Peter',
            'Petronilla',
            'Philomena',
            'Philpot',
            'Pierrot',
            'Pilate',
            'Pocahontas',
            'Polly',
            'Pontius',
            'Popeye',
            'Princip',
            'Rachael',
            'Rachaminoff',
            'Raleigh',
            'Ranx',
            'Raye',
            'Rayvon',
            'Reed',
            'Renee',
            'Rhee',
            'Rhiannon',
            'Rhoda',
            'Rhonda',
            'Richard',
            'Ricki',
            'Ricky',
            'Rigby',
            'Rimes',
            'Rita',
            'Robbie',
            'Rod',
            'Rogers',
            'Ronald',
            'Ronnie',
            'Rosie',
            'Ross',
            'Rota',
            'Roxanne',
            'Roxie',
            'Roy',
            'Ruehl',
            'Sadie',
            'Sakamoto',
            'Saloman',
            'Sally',
            'Samantha',
            'Sammy',
            'Samson',
            'Santayana',
            'Sara',
            'Sarahjane',
            'Schwartzkoff',
            'Scotty',
            'Scully',
            'Scylla',
            'Seamus',
            'Sean',
            'Seavers',
            'Sebastian',
            'Shaggy',
            'Sharons',
            'Sheena',
            'Sheila',
            'Shirl',
            'Shirley',
            'Siskel',
            'Smeaton',
            'Solomon',
            'Sophia',
            'Spears',
            'Seuss',
            'Shankly',
            'St',
            'Steptoe',
            'Steve',
            'Stipe',
            'Sundance',
            'Surratt',
            'Susannah',
            'Suzanne',
            'Suzie',
            'Suzy',
            'Sylvia',
            'Synghman',
            'Teddi',
            'Teresa',
            'Terry',
            'Tartanella',
            'Taylor',
            'Thatcher',
            'Thelmy',
            'Thomas',
            'Tim',
            'Timbaland',
            'Timmy',
            'Tina',
            'Tom',
            'Tommy',
            'Tony',
            'Tostig',
            'Tse',
            'Tung',
            'Tupac',
            'Ulysses',
            'Ustanov',
            'Valentino',
            'Valerie',
            'Velouria',
            'Vendell',
            'Vincent',
            'Visconte',
            'Vladimir',
            'Wagner',
            'Walcott',
            'Walter',
            'Welk',
            'Wendell',
            'Wendy',
            'Whitfield',
            'Whitney',
            'William',
            'Willie',
            'Wilson',
            'Wogan',
            'Yauch',
            'Yeats',
            'Yoakam',
            'Yoko',
            'Yoshimi',
            'Yul',
            'Zal',
            'Zally',
            'Zappa',
        ];
    }

    private function isName($word) {
        return in_array($word, $this->names);
    }

    private function setBrands() {
        // Things, No Doz, Cracker Jack organisation,
        $this->brands = [
            'ABC',
            'Accattone', // movie
            'Adidas',
            'Amtracks',
            'Armalite',
            'Armani',
            'Bacardi',
            'Baileys',
            'Barbie',
            'Batego',
            'BBC',
            'Beatlemania',
            'Benz',
            'Benzie',
            'Birchmount', //stadium
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
            'Dereon',
            'DNA',
            'Dolce',
            'Dorado',
            'Dow',
            'Doz',
            'Dupont',
            'Edsel',
            'Falstaff', // beer
            'Fedex',
            'Fendi',
            'Fu',
            'Gabbana',
            'Gideon',
            'Glock',
            'Gucci',
            'Halston',
            'Hardiflex',
            'Harpers',
            'Henri',
            'Hibs',
            'Hilton',
            'Hollywood',
            'Honda',
            'Hoover',
            'Horae',
            'Horten',
            'Indy',
            'IRS',
            'Jaycees',
            'JC',
            'Jeeps',
            'JFK',
            'Johnstown',
            'Karan',
            'Keds',
            'Khus',
            'KLF',
            'Kohoutek',
            'Kombi',
            'Kung',
            'Listerine',
            'Longines',
            'LST', // boat
            'Luger',
            'Marlboro',
            'Mastercard',
            'Maybelline',
            'MC',
            'MCs',
            'Mercedes',
            'Metro-Goldwyn',
            'Mets',
            'Morcheeba',
            'Moschino',
            'MTV',
            'NASA',
            'NBC',
            'Nescafe',
            'NF',
            'NRA',
            'NWA',
            'NYPD',
            'Nyquil',
            'Pabst',
            'Panaflex',
            'Penney',
            'Pennzoil',
            'Perignon',
            'PFC',
            'Porsches',
            'Reebok',
            'Revelaires',
            'Romilar', // cough syrup
            'Royce',
            'Scripto',
            'Smurf',
            'Soloflex',
            'Sony',
            'Stranraer', // soccer team
            'Symphonette', 
            // 'TAB',
            'Tampax',
            'Tesco',
            'Texaco',
            'Tropicana',
            'TRL', // tv show
            'Tylenol',
            'USO',
            'Vanetto',
            'Vegemite',
            'Visine',
            'Vogue',
            'VP',
            'Vuitton',
            'VW',
            'Walkman',
            'Walmart',
            'Winterman',
            'WLSD',
            'Wonderbread',
            'Woodbines',
            'Woody',
            'Wookiee',
            'Xanadu',
            'Yankees',
            'ZZ', // band - group/team
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
        // TODO return the case in the country, place etc
        if ($this->isCountry($tmp_word)) {
            return $tmp_word;
        }

        if ($this->isPlace($tmp_word)) {
            return $tmp_word;
        }

        if ($this->isMonth($tmp_word)) {
            return $tmp_word;
        }

        if ($this->isDay($tmp_word)) {
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
