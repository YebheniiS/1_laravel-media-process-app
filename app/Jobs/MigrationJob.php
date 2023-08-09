<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Lib\AWSToBunnyMigrator;
use App\Project;
use App\Lib\PushNotification;

class MigrationJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $project;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Project $project)
    {
        //
        $this->project = $project;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        //
        try {
            $pusher = new PushNotification();

            echo "Migration started: ".$this->project->id."\n";
            $migrator = new AWSToBunnyMigrator();
            // $this->project->update([
            //     'migration_status' => 1,
            // ]);
            $res = $migrator->migrate($this->project->id);
            if($res['success']) {
                $this->project->update([
                    'migration_status' => 2,
                ]);
                $pusher->channelWithId('migration', $this->project->user_id)->event('completed')->push([
                    'project_id' => $this->project->id,
                    'project_title' => $this->project->title
                ]);
            } else {
                $this->project->update([
                    'migration_status' => 0,
                ]);
                $pusher->channelWithId('migration', $this->project->user_id)->event('error')->push([
                    'project_id' => $this->project->id,
                    'project_title' => $this->project->title,
                    'message' => $res['message']
                ]);
            }
        } catch (\Exception $exception){

            $this->error("Message: " . $exception->getMessage() . "   Trace: " . $exception->getTraceAsString());
        }
    }
}
