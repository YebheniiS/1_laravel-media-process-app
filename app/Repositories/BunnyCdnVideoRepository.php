<?php


namespace App\Repositories;


use App\BunnyCdnVideo;
use App\Media;
use App\Services\BunnyCDN\BunnyStreamAdapter;
use App\Services\AnalyticsApi;

class BunnyCdnVideoRepository
{
    protected $bunnyVideo;
    protected $media;
    protected $adapter;

    public function __construct() {
        $this->adapter = new BunnyStreamAdapter();
    }

    /**
     *
     * @return BunnyCdnVideo
     * @throws \Exception
     */
    public function createVideo(Media $media)
    {
        $this->media = $media;
        
        // Need to ensure the media name is unique in the CDN, we add the media id
        // to the start as I assume it could be handy for debugging later
        $name = $this->media->id . "_" . guid();        
        $collectionId = $this->getCollectionId($this->media->encoded_size);
        
        // Create a new video in bunny CDN
        $this->adapter->createVideo($name, $this->media->encoded_size, $collectionId);
        echo "\nCreated";
        // Save the video id in our db so we can upload to it later
        $this->bunnyVideo = new BunnyCdnVideo();
        $this->bunnyVideo->name = $name;
        $this->bunnyVideo->bunny_cdn_video_id = $this->adapter->getVideoId();
        $this->bunnyVideo->media_id = $this->media->id;
        $this->bunnyVideo->collection_id = $collectionId;
        $this->bunnyVideo->save();
        $this->uploadVideo($this->media, $this->bunnyVideo);

        $analyticsApi = new AnalyticsApi();
 
        $analyticsApi->recordStorageUsed($media->user_id, $media->project_id, $media->id, $fileSize);
    }

    public function recreateVideo(Media $media, BunnyCdnVideo $bunnyVideo) {
        $name = $media->id . "_" . guid();

        $collectionId = $this->getCollectionId($media->encoded_size);
        // Create a new video in bunny CDN
        $this->adapter->createVideo($name, $media->encoded_size, $collectionId);
        echo "\nRecreated";

        // Save the video id in our db so we can upload to it later
        $bunnyVideo->name = $name;
        $bunnyVideo->bunny_cdn_video_id = $this->adapter->getVideoId();
        $bunnyVideo->media_id = $media->id;
        $bunnyVideo->collection_id = $collectionId;
        $bunnyVideo->save();
        $this->uploadVideo($media, $bunnyVideo);
    }

    public function uploadVideo(Media $media, BunnyCdnVideo $bunnyVideo)
    {
        $extension = getExtension($media->temp_storage_url);
        $file = file_get_contents($media->temp_storage_url);
        
        try {
            $this->adapter->uploadVideo(
                $bunnyVideo->bunny_cdn_video_id,
                $media->encoded_size,
                $file,
                $extension
            );
        }catch(\Exception $exception){
            echo $exception->getMessage();
        }
    }

    public function encodingComplete(BunnyCdnVideo $bunnyCdnVideo, $storageSize = 0)
    {
        $adapter = new BunnyStreamAdapter();

        $bunnyCdnVideo->load('media');

        // Just check the manifest hasn't already been populated by the
        // FE polling task
        if(! $bunnyCdnVideo->media->manifest_url) {
            $manifest = $adapter->getManifest($bunnyCdnVideo);

            $fileSize = $storageSize / 1024;
            Media::syncUpdateWithFe($bunnyCdnVideo->media, [
                'temp_storage_url' => '',
                'manifest_url' => $manifest,
                'storage_used' => $fileSize // needs to record storage used in KB
            ]);

            // Update analytics database
            $analyticsApi = new AnalyticsApi();
            // $analyticsApi->decreaseStorage($bunnyCdnVideo->media->user_id, $fileSize);
            $analyticsApi->recordStorageUsed($bunnyCdnVideo->media->user_id, $bunnyCdnVideo->media->project_id, $bunnyCdnVideo->media->id, $fileSize);
        }
    }

    public function checkEncodingStatus(BunnyCdnVideo  $bunnyCdnVideo)
    {
        $bunnyCdnVideo->load('media');


        $this->adapter = new BunnyStreamAdapter();

        $request  = $this->adapter->getVideo($bunnyCdnVideo->bunny_cdn_video_id, $bunnyCdnVideo->media->encoded_size);

        if($request['status'] > $bunnyCdnVideo->status) {
            $bunnyCdnVideo->status = $request['status'];
            $bunnyCdnVideo->save();
        }

        if($request['status']===4) {
            $this->encodingComplete($bunnyCdnVideo, $request['storageSize']);
        }

        return $bunnyCdnVideo;
    }

    private function getCollectionId($resolution)
    {
        $appEnv = strtolower(env('APP_ENV'));
        $configString =  "bunnyCdn.stream_api.{$resolution}.collection_id_{$appEnv}";
        return config($configString);
    }

}