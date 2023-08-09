<?php

namespace App\Console\Commands;

use App\Lib\AWSToBunnyMigrator;
use Illuminate\Console\Command;

class migrateVideos extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'migrate:videos {projectId}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Migrates all the videos from AWS to BunnyCDN';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        echo "Migration started\n";
        $migrator = new AWSToBunnyMigrator();
        $projectId = $this->argument('projectId');
        $migrator->migrate($projectId);
        echo "Migration done";
    }
}
