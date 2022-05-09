<?php

namespace App\Console\Commands;

use App\Jukebox\Dictionary\CategoryInterface as Category;
use App\Jukebox\Dictionary\WordCloudInterface as WordCloud;
use App\Jukebox\Song\SongInterface as Song;
use DB;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Redis;
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
     * The song interface
     *
     * @var App\Jukebox\Song\SongInterface
     */
    private $song;

    /**
     * The wordCloud interface
     *
     * @var App\Jukebox\Dictionary\WordCloudInterface
     */
    private $wordCloud;

    /**
     * The category interface
     *
     * @var App\Jukebox\Dictionary\CategoryInterface
     */
    private $category;

    /**
     * Constructor
     */
    public function __construct(Song $song, WordCloud $wordCloud, Category $category)
    {
        parent::__construct();
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
        foreach ($this->song->allByConstraints() as $song):
            if (! Storage::disk(config('filesystems.partition'))->has(config('filesystems.media_directory') . $song->location)):
                $this->info($song->artists[0]->artist . ' ' . $song->title . ' LOCATION: ' . $song->location . ' does not exist');
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
        foreach ($this->song->allByConstraints() as $song):
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
        $wordCloud = $this->wordCloud->allByConstraints();
        foreach ($wordCloud as $word):
            DB::table('word_category')->insert(['word_cloud_id' => $word->id, 'category_id' => $categories[$word->category]]);
        endforeach;
    }
}
