<?php

namespace App\Repositories;

use App\EncodedStockVideos;
use App\Helper\VideoUrlHelper;
use App\Lib\PushNotification;
use App\Lib\ThumbnailGenerator;
use App\Scopes\MediaScope;
use App\Media;
use App\Project;
use App\QencodeTask;
use App\Services\BunnyCDN\BunnyEdgeStorageAdapter;
use App\Services\FineUploader;
use App\Services\VideoCompressionService;
use App\Models\User;
use Doctrine\DBAL\Query\QueryBuilder;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use function Aws\manifest;

class MediaRepository {
    protected $media;
    protected $projectRepo;
    protected $videoUrlHelper;
    protected $encodedStockVideos;
    protected $qEncodeTaskRepo;
    protected $fineUploader;
    protected $videoCompressionService;
    protected $pusher;

    public function __construct(
        Media $media,
        ProjectRepository $projectRepo,
        VideoUrlHelper $videoUrlHelper,
        EncodedStockVideos $encodedStockVideos,
        QencodeTaskRepository $qEncodeTaskRepo,
        FineUploader $fineUploader,
        VideoCompressionService $videoCompressionService
    )
    {
        $this->pusher = new PushNotification();
        $this->media = $media;
        $this->projectRepo = $projectRepo;
        $this->videoUrlHelper = $videoUrlHelper;
        $this->encodedStockVideos = $encodedStockVideos;
        $this->qEncodeTaskRepo = $qEncodeTaskRepo;
        $this->fineUploader = $fineUploader;
        $this->videoCompressionService = $videoCompressionService;
    }

    /**
     * Using a resolver function here instead raw grahpQL mutation as we
     * need to move some things around in s3 and mess with some URL's for
     * the CDN
     *
     * @param $mediaItem
     * @return Builder|Model
     */
    public function create($inputs) {
        $newMediaItem = [
            'name' => $inputs["name"],
            'is_image' => $inputs['is_image'],
            'project_id' => $inputs['project_id'],
            'media_size' => $inputs['media_size']
        ];

        if(isset($inputs["temp_storage_url"])) {
            $newMediaItem["temp_storage_url"] = $inputs["temp_storage_url"];
        }

        if(isset($inputs["url"])) {
            $newMediaItem["url"] = $inputs["url"];
        }

        if(isset($inputs["manifest_url"])) {
            $newMediaItem["manifest_url"] = $inputs["manifest_url"];
            $newMediaItem["thumbnail_url"] =  $inputs["thumbnail_url"];
            if($inputs['project_id']) {
                // Save the encoded size from the project I think it will be handy to
                // have this later
                $project = Project::findOrFail($inputs['project_id']);

                $newMediaItem["encoded_size"] = $project->video_encoding_resolution;
            }
            return $this->media->query()->create($newMediaItem);
        }

        // We have have an item in temp storage url we need to move it
        if(isset($inputs['temp_storage_url'])) {
            if($inputs['is_image']) {
                // If the media item is an image we can move the file from temp
                // storage to bunny cdn here
                $file = file_get_contents($inputs['temp_storage_url']);

                $adapter = new BunnyEdgeStorageAdapter();

                $extension = getExtension($inputs['temp_storage_url']);

                $url = $adapter->uploadImage($file, $extension);

                $newMediaItem['thumbnail_url']  = $url;
                $newMediaItem['temp_storage_url'] = "";
            }
            else {
                // Videos will have a thumbnail
                $newMediaItem["thumbnail_url"] =  $inputs["thumbnail_url"];

                if($inputs['project_id']) {
                    // Save the encoded size from the project I think it will be handy to
                    // have this later
                    $project = Project::findOrFail($inputs['project_id']);
    
                    $newMediaItem["encoded_size"] = $project->video_encoding_resolution;
                }
            }
        }

        return $this->media->query()->create($newMediaItem);
    }

    /**
     * Copy a media to new project
     *
     * @param $id
     * @param $newProjectId
     * @return mixed
     */
    public function copyToProject($id, $newProjectId, $userId=null)
    {
        $mediaToCopy = Media::query()->withoutGlobalScope(MediaScope::class)->findOrFail($id);
        $newMedia = $mediaToCopy->replicate();
        $newMedia->project_id = $newProjectId;

        if(isset($userId)) {
            $newMedia->save();
            $newMedia->user_id = $userId;
        }

        $newMedia->save();

        return $newMedia;
    }

    /**
     * Compress video file to given
     * @param $id
     * @param $type
     * @return bool
     * @throws \Exception
     */
    public function compressVideo($id, $type)
    {
        $this->videoCompressionService->compressVideo($id, $type);

        return true;
    }

    /**
     * @param $orderBy
     * @param $args
     * @return mixed
     */
    public function sortMedias($orderBy, $args)
    {
        // Soft Deletes not working on this model so had to add this deleted_at flag manually
        // Same with user scope we may have to redo without DB table later but for now this will
        // do the trick
        $query = DB::table('media')->where('deleted_at', NULL);
        $projectIds = auth()->user()->projects;
        if($projectIds != NULL) {
            $query->whereIn('project_id', $projectIds)->where("user_id", auth()->user()->parent_user_id);
        } else {
            $query->where("user_id", auth()->user()->id);
        }
        if(isset($args['project_id'])) {
            $query->where('project_id', $args['project_id']);
            $project = Project::query()->findOrFail($args['project_id']);

            if($project->base_width == 720) {
                $query->where('media_size', '16:9');
            }
    
            if($project->base_width == 540) {
                $query->where('media_size', '4:3');
            }
    
            if($project->base_width == 228) {
                $query->where('media_size', '9:16');
            }    
        }

        if(isset($args['is_image'])) {
            $query->where('is_image', $args['is_image']);
        }

        if(isset($args['search'])) {
            $query->where('name', 'LIKE', '%'. $args['search'] .'%');
        }


        if(isset($args['not_project_id'])) {
            $query->where('project_id','!=', $args['not_project_id']);
        }

        // For filter based on media ratio to be matched to the project ratio
        if(isset($args['media_size'])) {
            if($args['media_size'] == '16:9') {
                $query->where(function ($query) {
                    $query->where('media_size', '16:9')->orWhereNull('media_size');
                });
            } else {
                $query->where('media_size', $args['media_size']);
            }
        }

        return $query->orderByRaw($orderBy);
    }

    /**
     * Update the media item and push the
     * update to the FE
     *
     * @param $id
     * @param $data
     */
    public function updateMedia($id, $data)
    {
        $media = Media::findOrFail($id);
        $media->update($data);
        $this->pusher->channel('media')->event('update');
        $this->pusher->push($data);
    }
}