<?php

namespace App\Services;

use App\Jobs\MigrationJob;
use App\PlayerVersion;
use App\Repositories\ProjectRepository;
use App\Services\BunnyCDN\BunnyEdgeStorageAdapter;
use App\Services\AwsStorageAdapter;
use App\Services\ProjectPublisher\ProjectPublisherInterface;
use Exception;
use Illuminate\Filesystem\FilesystemAdapter;
use Illuminate\Support\Facades\Storage;
use App\Lib\PushNotification;

class ProjectPublisher implements ProjectPublisherInterface
{
    private $disk = 's3';
    /* @var FilesystemAdapter Storage */
    private $storage; // bunnyCDN
    private $oldStorage; // AWS
    private $projectRepository;
    private $migrator;
    private $basePath = 'projects';

    public function __construct(ProjectRepository $projectRepository)
    {
        $this->storage = new BunnyEdgeStorageAdapter();
        $this->oldStorage = new AwsStorageAdapter();
        $this->projectRepository = $projectRepository;
    }

    /**
     * publish a project to s3
     *
     * @param Int $id
     * @return mixed
     * @throws \Throwable
     */
    public function publish($id)
    {
        // First get all the data from the project repo
        [$nodes, $modals, $videos, $project, $sharePageUrl] = $this->projectRepository->getPlayerData($id);

        // Check no videos are still being encoded
        foreach ($videos as $video) {
          if ($video &&  $video->temp_storage_url && ! $video->manifest_url) {
            throw new Exception('Some project medias are still being encoded. Please try again.');
          }
        }
 
        /*******************  Migration Code here *****************/
        if($project->migration_status == 0) { // 0 means not migrated
            
            MigrationJob::dispatch($project)->onQueue('migration');
            // throw new Exception('Project Migration started now. Please try again after some time.');
         
            $pusher = new PushNotification();
             
            $pusher->channel('migration')->event('started')->push([
                'project_id' => $project->id,
                'project_title' => $project->title,
            ]);
            
            $project->update([
                'migration_status' => 1,
            ]);
            return ;
        } else if($project->migration_status == 1) { // 1 means migrating
            $pusher = new PushNotification();
       
            $pusher->channel('migration')->event('processing')->push([
                'project_id' => $project->id,
                'project_title' => $project->title,
            ]);
            return ;
            // throw new Exception('Project is being migrated now. Please try again after some time.');
        }
        /***********************************************************/

        // Next we need the s3 storage path
        $path = $this->getStoragePath($project);

        // Don't save the analytics if on local
        $analytics = (env('APP_ENV') !== 'local');
     

        // Generate the static view html file of the player
//        $contents = $this->view([
//            $nodes, $modals, $videos, $project, $analytics, $sharePageUrl
//        ])->render();
        // $contents = "window['pdqplyr-{$project->storage_path}'] = ";
        $contents = \Safe\json_encode([
            'nodes' => $nodes,
            'modals' => $modals,
            'videos' => $videos,
            'project' => $project,
            'analytics' => $analytics,
            'sharePageUrl' => $sharePageUrl
        ]);
 

        // Push the static HTML up to storage
        $published_path = $this->storage->uploadProject($contents, "$path/data.json");

        // Finally Purge the path on the cdn so we enable to new project
        if (env('CDN_URL')) {
            $this->purgeCache($path .  '/index.html');
        }
    

        // Update the path and last published date on the project and return the project back
        $project->update([
            'storage_path' => $path,
            'published_path' => $published_path,
            'published_at' => date("Y-m-d H:i:s")
        ]);

        $this->storage->purge($published_path);
    

        // Return a fresh instance of the project
        return $project->fresh();
    }

    /**
     * Unpublish a project by removing the index.html file from s3
     *
     * @param Int $id
     * @return mixed
     */
    public function unpublish($id)
    {
        $project = $this->projectRepository->getProject($id);

        if($project->published_path && !str_starts_with($project->published_path, env('CDN_DOMAIN'))) {
            $this->storage->purge($project->published_path);
            if($project->storage_path){
                $this->storage->deleteProject($project);
            }
        } else {
            $this->oldStorage->delete($project->storage_path);
        }

        // remove published mark
        $project->update([
            'published_at' => null,
            'published_path' => null
        ]);

        return $project;
    }

    /**
     * Returns the blade html view for a project.
     *
     * @param array $data
     * @param bool $playing
     * @param bool $playerEnv
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View|mixed
     */
    public function view($data, $playing = true, $playerEnv = false)
    {
        [$nodes, $modals, $videos, $project, $sharePageUrl, $analytics] = $data;

        $jsonData = [];
        $jsonData['project'] = $project;
        $jsonData['nodes'] = $nodes;
        $jsonData['videos'] = $videos;
        $jsonData['modals'] = $modals;
        $jsonData['sharePageUrl'] = $sharePageUrl;

        return json_encode($jsonData);

        // if($playerEnv && $playerEnv === 'local' || $playerEnv === 'local2') {
        //     $playerRoot = 'http://localhost';
        // }
        // else {
        //     $playerRootUrl = env('PDQ_PLAYER_ROOT_URL');

        //     $versionsJsonFile = file_get_contents($playerRootUrl . '/versions.json');

        //     $version = array_first(json_decode($versionsJsonFile));
        //     $playerRoot = $playerRootUrl . '/' . $version;
        // }

        // return view('player-new')
        //     ->with('project', $project)
        //     ->with('nodes', $nodes)
        //     ->with('modals', $modals)
        //     ->with('videos', $videos)
        //     ->with('playerRoot', $playerRoot)
        //     ->with('apiUrl', env('API_URL'))
        //     ->with('playing', (int)$playing)
        //     ->with('playerEnv', $playerEnv)
        //     ->with('analytics', $analytics)
        //     ->with('env', env('APP_ENV'))
        //     ->with('sharePageUrl', $sharePageUrl);
    }

    /**
     * Purge the file from the fastly cache
     *
     * @param $path
     */
    protected function purgeCache($path)
    {
        $url = env('CDN_URL') . $path;
        if (strpos($url, 'global.ssl.fastly.net')) {
            $s = curl_init();
            curl_setopt($s, CURLOPT_URL, $url);
            curl_setopt($s, CURLOPT_CUSTOMREQUEST, 'PURGE');
            curl_setopt($s, CURLOPT_RETURNTRANSFER, 1); // prevent the response being printed
            curl_exec($s);
            curl_close($s);
        }
    }
    
    /**
     * Generate a unique id to use as the storage path folder
     * for the project
     *
     * @return string
     */
    protected function generateStoragePath($project)
    {
        return  $this->basePath . '/' . uniqid();
    }

    /**
     * Get the storage path of the project in s3
     *
     * @param $project
     * @return string
     */
    protected function getStoragePath($project)
    {
        if (isset($project->storage_path)) {
            return $project->storage_path;
        }

        return $this->generateStoragePath($project);
    }
}
