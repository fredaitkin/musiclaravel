<?php

namespace App\Console\Commands;

use Exception;
use Illuminate\Console\Command;

class ConvertAppleMusic extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ffmpeg:convert
                            {--song= : The song with directory}
                            {--dir= : The song directory}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Convert apples songs to mp3 format';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
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

            $return = shell_exec(sprintf("which %s", escapeshellarg('ffmpeg')));
            if (! $return):
                $this->error('The command ffmpeg does not exist or is not configured on your system');
                exit;
            endif;

            if (isset($options['song'])):
                if (strpos($options['song'], '.mp4') !== false):
                    $new_file = str_replace('.mp4', '.mp3', $options['song']);
                    $command = 'ffmpeg -i "' . $options['song'] . '" "' . $new_file . '"';
                    exec($command);
                endif;
            endif;

            if (isset($options['dir'])):
                if (! is_dir($options['dir'])):
                    $this->error('Invalid diretory path');
                    exit;
                endif;

                foreach (scandir($options['dir']) as $file):
                    if ($file !== '.' && $file !== '..' && $file != '.DS_Store'):
                        if (strpos($file, '.mp4') !== false):
                            $this->info($file);
                            $new_file = str_replace('.mp4', '.mp3', $file);
                            $command = 'ffmpeg -i "' . $options['dir'] . '/' . $file . '" "' . $options['dir'] . '/' . $new_file . '"';
                            exec($command);
                        endif;
                    endif;
                endforeach;
            endif;

            $this->info('The songs have been converted.');
        } catch (Exception $e) {
            $this->error('The conversion process has been failed: ' . $e->getMessage());
        }
    }
}
