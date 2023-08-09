<?php

namespace App\Console\Commands;

use App\Project;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Storage;

class republishAllSharePages extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'custom:publishSharePages';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

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
            $root = 'https://s3.us-east-2.amazonaws.com/interactrapp.com';

            // Get projects
            $this->line("Getting Projects");
            $projects = Project::whereNotNull('storage_path')->get();

            // Show Results
            $count = count($projects);
            $this->line("Found {$count} projects");

            // Publish
            foreach($projects as $key => $project){
                try {
                    // Get project HTML
                    $this->line("Getting HTML from  {$project->storage_path} ");
                    $split = explode('/', $project->storage_path);
                    $path = '/share/' . $split[1];

                    $url = $root . $path;
                    $this->info('Getting.. ' . $url);

                    $dirty_html = file_get_contents( $url);

                    // Remove virus
                    $this->line('Cleaning');
                    $clean_html = $this->clean($dirty_html);

                    // Put clean file on s3
                    Storage::disk('app')->put($path, $clean_html, ['public']);
                    $this->info('Cleaned!');

                }catch(\Exception $e){
                    $this->error("Message: " . $e->getMessage() . "   Trace: " . $e->getTraceAsString());
                }
            }

        }catch(\Exception $exception){
            $this->error("Message: " . $exception->getMessage() . "   Trace: " . $exception->getTraceAsString());
        }
    }

    private function clean($str) {
        $string = '<link href="/main.css" rel="stylesheet">';
        $position = strpos($str, "</head>");

        return substr_replace( $str, $string, $position, 0 );
    }
}
