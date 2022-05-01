<?php

namespace App\Console\Commands;

use App\Music\Dictionary\CategoryInterface as Category;
use App\Jukebox\Song\SongInterface as Song;
use App\Music\Dictionary\WordCloudInterface as WordCloud;
use DB;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Redis;
use Log;
use Storage;

class PerformDataFix extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'db:df
                            {--missing-songs : Report on missing songs}
                            {--resave-artists : Copy artist ids to pivot table}
                            {--fix-categories : Move category to pivot table}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fix db data issues';

    /**
     * Command options
     *
     * @var array
     */
    private $options;

    /**
     * The media directory
     *
     * @var string
     */
    private $media_directory;

    /**
     * The song interface
     *
     * @var App\Jukebox\Song\SongInterface
     */
    private $song;

    /**
     * The wordCloud interface
     *
     * @var App\Music\Dictionary\WordCloudInterface
     */
    private $wordCloud;

    /**
     * The category interface
     *
     * @var App\Music\Dictionary\CategoryInterface
     */
    private $category;

    /**
     * Constructor
     */
    public function __construct(Song $song, WordCloud $wordCloud, Category $category)
    {
        parent::__construct();
        $this->media_directory = Redis::get('media_directory');
        $this->song = $song;
        $this->wordCloud = $wordCloud;
        $this->category = $category;
    }

    /*
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->options = $this->options();

        if ($this->options['missing-songs']):
            $this->reportMissingSongs();
        endif;

        if ($this->options['resave-artists']):
            $this->updateArtistPivotTable();
        endif;

        if ($this->options['fix-categories']):
            $this->info('This function is obsolete');
        endif;
    }

    /**
     * Serialize previous teams
     *
     * @return mixed
     */
    private function reportMissingSongs()
    {
        foreach ($this->song->all(false) as $song):
            if (! Storage::disk(config('filesystems.partition'))->has($this->media_directory . $song->location)):
                Log::info($song->artists[0]->artist . ' ' . $song->title . ' LOCATION: ' . $song->location . ' does not exist');
            endif;
        endforeach;
    }

    /**
     * Copy artist id and song id to pivot table
     *
     * @return mixed
     */
    private function updateArtistPivotTable()
    {
        foreach ($this->song->all() as $song):
            DB::insert('insert into artist_song (song_id, artist_id) values (?, ?)', [$song->id, $song->artist_id]);
        endforeach;
    }

    /**
     * Move categories to pivit table
     */
    private function fixCategories()
    {
        // Retrieve categories
        $records = $this->category->all();
        $categories = [];
        foreach($records as $record) {
            $categories[$record->category] = $record->id;
        }
        // Insert category id into pivot table
        $wordCloud = $this->wordCloud->wordCloud();
        foreach ($wordCloud as $word):
            DB::table('word_category')->insert(['word_cloud_id' => $word->id, 'category_id' => $categories[$word->category]]);
        endforeach;
    }
}
