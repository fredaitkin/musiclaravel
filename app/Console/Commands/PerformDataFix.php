<?php

namespace App\Console\Commands;

use App\Category\Category;
use App\Music\Song\Song;
use App\Words\WordCloud;
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
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->media_directory = Redis::get('media_directory');
        parent::__construct();
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
            $this->fixCategories();
        endif;
    }

    /**
     * Serialize previous teams
     *
     * @return mixed
     */
    private function reportMissingSongs()
    {
        foreach (Song::all() as $song):
            if (! Storage::disk(config('filesystems.partition'))->has($this->media_directory . $song->location)):
                Log::info($song->artist->artist . ' ' . $song->title . ' LOCATION: ' . $song->location . ' does not exist');
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
        foreach (Song::all() as $song):
            DB::insert('insert into artist_song (song_id, artist_id) values (?, ?)', [$song->id, $song->artist_id]);
        endforeach;
    }

    /**
     * Move categories to pivit table
     */
    private function fixCategories()
    {
        // Retrieve categories
        $records = Category::all();
        $categories = [];
        foreach($records as $record) {
            $categories[$record->category] = $record->id;
        }
        // Insert category id into pivot table
        $wordCloud = WordCloud::select('id', 'category')->whereNotNull('category')->where('category', '<>', '')->get();
        foreach ($wordCloud as $word):
            DB::table('word_category')->insert(['word_cloud_id' => $word->id, 'category_id' => $categories[$word->category]]);
        endforeach;
    }
}
