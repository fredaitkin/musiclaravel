<?php

/**
 * PerformDataFix.php
 *
 * @package Jukebox
 * @author  Melissa Aitkin
 */

namespace App\Console\Commands;

use App\Jukebox\Dictionary\CategoryInterface as Category;
use App\Jukebox\Dictionary\WordCloudInterface as WordCloud;
use App\Jukebox\Song\SongInterface as Song;
use DB;
use Illuminate\Console\Command;
use Mail;
use Storage;

/**
 * Tools to fix Jukebox data via the command line
 */
class PerformDataFix extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'db:df
                            {--missing-songs : Report on missing songs}
                            {--mss : Mismatched song details by storage}
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
     *
     * @param App\Jukebox\Song\SongInterface            $song      Song interface
     * @param App\Jukebox\Dictionary\WordCloudInterface $wordCloud WordCloud interface
     * @param App\Jukebox\Dictionary\CategoryInterface  $category  Category interface
     */
    public function __construct(Song $song, WordCloud $wordCloud, Category $category)
    {
        parent::__construct();
        $this->song = $song;
        $this->wordCloud = $wordCloud;
        $this->category = $category;
        $this->partition_root = config('filesystems.disks')[config('filesystems.partition')]['root'];
        $this->media_directory = config('filesystems.media_directory');
    }

    /**
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

        if ($this->options['mss']):
            $this->reportMissingSongsByStorage();
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
     * Move categories to piv0t table
     *
     * @return void
     */
    private function fixCategories()
    {
        // Retrieve categories
        $records = $this->category->all();
        $categories = [];
        foreach ($records as $record):
            $categories[$record->category] = $record->id;
        endforeach;
        // Insert category id into pivot table
        $wordCloud = $this->wordCloud->allByConstraints();
        foreach ($wordCloud as $word):
            DB::table('word_category')->insert(['word_cloud_id' => $word->id, 'category_id' => $categories[$word->category]]);
        endforeach;
    }

    /**
     * Loop over media directory and check song information is correct.
     *
     * @return void
     */
    private function reportMissingSongsByStorage()
    {
        $now = date('YmdHi');
        $this->filename = 'report-' . $now . '.csv';
        $this->report_title = 'File Location - ' . $now;
        $this->records = [];

        $scan_items = glob($this->partition_root . $this->media_directory . '*');
        foreach($scan_items as $item):
            if(is_dir($item)):
                $this->processArtistDirectory($item);
            endif;
        endforeach;

        $this->createCSVReport($this->records, ['Song location']);
        $this->sendFile();
    }

    /**
     * Loop over artist directory and check song information is correct.
     *
     * @param string $artist_dir Artist directory
     *
     * @return void
     */
    private function processArtistDirectory(string $artist_dir)
    {
        $scan_albums = glob($artist_dir . '/*');
        foreach($scan_albums as $album):
            if(is_dir($album)):
                $this->processAlbum($album);
            endif;
        endforeach;
    }

    /**
     * Process album checking song location is correct
     *
     * @param string $album Artist album
     *
     * @return void
     */
    private function processAlbum(string $album)
    {
        $scan_songs = glob($album . '/*');
        foreach($scan_songs as $title):
            if($this->song->isSong($title)):
                $location = str_replace($this->partition_root . $this->media_directory, '', $title);
                // This doesn't account for backwards versus forwards slashes which are still playable.
                // Ani Di France Bob Womack - Change backwards to forwards?
                $song = $this->song->allByConstraints(['location' => $location]);
                if ($song->count() === 0):
                    $this->records[] = $location;
                endif;
            endif;
        endforeach;
    }

    /**
     * Create CSV file
     *
     * @param Array $records Report data $paramname
     * @param Array $header  Report header
     *
     * @return void
     */
    protected function createCSVReport(array $records, array $header = null)
    {
        if (isset($records[0])):

            $handle = fopen($this->filename, 'w');

            // Add  report title
            fputcsv($handle, [$this->report_title]);
            fputcsv($handle, []);

            // Add report header
            if (! $header):
                 $header = (array) $records[0];
                 $header = array_keys($header);
            endif;

            fputcsv($handle, $header);

            // Add report data
            foreach($records as $record):
                $record = (array) $record;
                fputcsv($handle, array_values($record));
            endforeach;

            fclose($handle);
            $this->info('The report has been run successfully.');

        else:
            $this->error('No records were returned by this query');
        endif;

    }

    /**
     * Send report via email
     *
     * @return void
     */
    protected function sendFile()
    {
        $data = ['report' => $this->report_title];
        Mail::send(
            'mail_report', $data, function ($message) {
                $message->to(config('mail.report_email'), 'Report Email Address')->subject('MyMusic Report');
                $message->attach(base_path() . DIRECTORY_SEPARATOR . $this->filename);
                $message->from(config('mail.admin_email'), 'MyMusic');
            }
        );
    }
}
