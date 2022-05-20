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
                            {--song= : The song}
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

            if (! isset($options['dir'])):
                $this->error('Directory is required');
                exit;
            endif;

            if (! is_dir($options['dir'])):
                $this->error('Invalid diretory path');
                exit;
            endif;

            $files = array();
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

            $this->info('The songs have been converted.');
        } catch (Exception $e) {
            $this->error('The conversion process has been failed: ' . $e->getMessage());
        }
    }
}
