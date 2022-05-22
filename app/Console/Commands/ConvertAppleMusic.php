<?php

namespace App\Console\Commands;

use Exception;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Log;

class ConvertAppleMusic extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ffmpeg:convert
                            {--list : List songs in mp4 format}
                            {--song= : The song to convert}
                            {--artist= : The artist to convert}
                            {--album-dir= : The album directory}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Convert apples songs to mp3 format';

    /**
     * The root directory.
     *
     * @var string
     */
    protected $root_dir;

    /**
     * The media directory.
     *
     * @var string
     */
    protected $media_dir;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        $this->root_dir = Config::get('filesystems.disks')[Config::get('filesystems.partition')]['root'];
        $this->media_dir = Config::get('filesystems.media_directory');
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        try {

            $options = $this->options();

            // Does the ffmpeg tool exist?
            $return = shell_exec(sprintf("which %s", escapeshellarg('ffmpeg')));
            if (! $return):
                $this->error('The command ffmpeg does not exist or is not configured on your system');
                exit;
            endif;

            // Log a list of mp4 songs.
            if (isset($options['list'])):
                $iter = new \GlobIterator($this->root_dir . $this->media_dir . '*/*/*.mp4');
                foreach($iter as $file){
                    Log::info($file);
                }
            endif;

            // Convert a song.
            if (isset($options['song'])):
                if (strpos($options['song'], '.mp4') !== false):
                    $new_file = str_replace('.mp4', '.mp3', $options['song']);
                    $command = 'ffmpeg -i "' . $options['song'] . '" "' . $new_file . '"';
                    exec($command);
                    $this->info('The song has been converted.');
                endif;
            endif;

            // Convert songs in an album.
            if (isset($options['album-dir'])):
                if (! is_dir($options['album-dir'])):
                    $this->error('Invalid diretory path');
                    exit;
                endif;

                foreach (scandir($options['album-dir']) as $file):
                    if ($file !== '.' && $file !== '..' && $file != '.DS_Store'):
                        if (strpos($file, '.mp4') !== false):
                            $this->info($file);
                            $new_file = str_replace('.mp4', '.mp3', $file);
                            $command = 'ffmpeg -i "' . $options['dir'] . '/' . $file . '" "' . $options['dir'] . '/' . $new_file . '"';
                            exec($command);
                        endif;
                    endif;
                endforeach;
                $this->info('The songs have been converted.');
            endif;

            // Convert songs by an artist.
            if (isset($options['artist'])):
                if (! is_dir($this->root_dir . $this->media_dir . $options['artist'])):
                    $this->error('Invalid diretory path for artist');
                    exit;
                endif;

                $iter = new \GlobIterator($this->root_dir . $this->media_dir . $options['artist'] . '/*/*.mp4');
                foreach ($iter as $file):
                    $this->info($file);
                    $new_file = str_replace('.mp4', '.mp3', $file);
                    $command = 'ffmpeg -i "' . $file . '" "' . $new_file . '"';
                    exec($command);
                endforeach;
            endif;

        } catch (Exception $e) {
            $this->error('The conversion process has been failed: ' . $e->getMessage());
        }
    }
}
