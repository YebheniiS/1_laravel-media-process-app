<?php

namespace App\Console\Commands;

use App\Lib\PreloadConverter;
use App\Media;
use App\Project;
use App\Scopes\UserScope;
use App\Models\User;
use Illuminate\Console\Command;

class UpgradeToEvolution20 extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update:evolution20';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Upgrade the database to evolution version 2.0';

    protected $preloadConverter;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();

        $this->preloadConverter = new PreloadConverter();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
//        $users = User::all()->pluck('id');
        $users = [1];

        $this->line("Processing " . count($users) . " users");
        foreach ($users as $user){
            $this->line("Processing User: " . $user);
            $projects = Project::where('user_id', $user)->with('nodes')->get();

            foreach($projects as $project) {
                $this->line("Processing Project: " . $project->id);
                $updated = false;

                if(!$project->player_skin) {
                    $project->player_skin= Project::DEFAULT_PLAYER_SKIN;
                    $updated = true;
                }

                if(!$project->share_data) {
                    $project->share_data = Project::DEFAULT_SHARE_DATA;
                    $updated = true;
                }

                if($updated) {
                    $project->save();
                }

                foreach ($project->nodes as $node){
                    $this->line("Processing Node: " . $node->id);
                    $node->user_id = $user;
                    $node->save();

                    $node->load('interactions');
                    foreach($node->interactions as $interaction){
                        $this->line("Processing Interaction: " . $interaction->id);
                        $interaction->user_id = $user;
                        $interaction->save();
                    }
                }
            }

            $media = Media::query()->where('user_id', $user)->get();

            foreach ($media as $video) {
                if (isset($video->manifest_url)) {
                    $this->line("Processing Media Preload Script: " . $video->id);
                    $preloadPlaylistUrl = $this->preloadConverter->getPreloadPlaylist($video->manifest_url);

                    if (isset($preloadPlaylistUrl)) {
                        $this->preloadConverter->updateMediaPreloadUrl($video, $preloadPlaylistUrl);
                    }
                }
            }

            $this->line("Completed User: " . $user);
        }
    }
}
