<?php
namespace App\Lib;

use App\Lib\FileHelper;
use App\Media;
use App\Project;
use App\Services\BunnyCDN\BunnyEdgeStorageAdapter;
use App\Services\AnalyticsApi;

class AWSToBunnyMigrator
{

    /**
     * Create a new job instance.
     *
     * AWSToBunnyMigrator constructor.
     */
    //     <!-- PHP Class AWSToBunnyMigrator
    // Receives Project ID
    // Gets  all media for project
    // If media->url && media.url startsWith https://swiftcdn6.global.ssl.fastly.net
    //   Move the mp4 file from aws to bunny legacy bucket
    // Else if media->manfest url && media.manifestUrl startsWith https://swiftcdn6.global.ssl.fastly.net
    //   Download the manifest URL and scrape to get all the video files,
    //   Then move manifest and all files from aws to bunny legacy
    // If all media is moved successfully set project->migrated_to_bunny = 1; -->

    protected $adapter;
    protected $analyticsApi;

    public function __construct()
    {
        $this->adapter = new BunnyEdgeStorageAdapter();
        $this->analyticsApi = new AnalyticsApi();
    }

    public function migrate($projectId) {
        try {
            $medias = Media::query()->where('project_id', $projectId)->get();
            // $project = Project::query()->where('id', $projectId)->get();
            foreach ($medias as $media) {
                
                // migrate thumbnails
                if($this->checkUrlForMigration($media->thumbnail_url)) {
                    $fileSize = FileHelper::getRemoteFileSize($media->thumbnail_url); // file size is in KB
                    echo "thumbnail file size".$fileSize."\n";
                    
                    $this->checkStorage($media->user_id, $fileSize);

                    $extension = getExtension($media->thumbnail_url);   
                    $file = file_get_contents($media->thumbnail_url);
                    $bunnyUrl = $this->adapter->uploadImage(
                        $file,
                        $extension
                    );

                    $media->update([
                        'thumbnail_url' => $bunnyUrl,
                        'storage_used' => $fileSize
                    ]);

                    // Update analytics database
                    // $this->analyticsApi->decreaseStorage($media->user_id, $fileSize);
                    $this->analyticsApi->recordStorageUsed($media->user_id, $media->project_id, $media->id, $fileSize);
                }

                // migrate videos
                if($this->checkUrlForMigration($media->url)) {
                    $fileSize = FileHelper::getRemoteFileSize($media->url); // file size is in KB
                    echo "video file size".$fileSize."\n";
                    
                    $this->checkStorage($media->user_id, $fileSize);

                    $extension = getExtension($media->url);
                    $file = file_get_contents($media->url);
                    
                    echo "Uploading ".$media->url."\n";
                    $bunnyUrl = null;
                    if($media->is_image) {
                        $bunnyUrl = $this->adapter->uploadImage(
                            $file,
                            $extension
                        );
                    } else {
                        $bunnyUrl = $this->adapter->uploadVideo(
                            $file,
                            $extension,
                            $projectId
                        );
                    }
                    
                    echo "Uploaded to ".$bunnyUrl."\n";
                    $media->update([
                        'url' => $bunnyUrl,
                        'storage_used' => $fileSize
                    ]);

                    // Update analytics database
                    // $this->analyticsApi->decreaseStorage($media->user_id, $fileSize);
                    $this->analyticsApi->recordStorageUsed($media->user_id, $media->project_id, $media->id, $fileSize);
                } else if($this->checkUrlForMigration($media->manifest_url)) {
                    
                    $prefix = str_replace('playlist.m3u8', '', $media->manifest_url);
                    [$m3u8Files, $mediaFiles, $fileSize] = $this->getAllMedias($media->manifest_url, $prefix);
                    echo "m3u8 file size".$fileSize."\n";
                    
                    $this->checkStorage($media->user_id, $fileSize);

                    $mediaPath = $projectId . "/" . guid();
                    $bunnyUrl = $this->uploadFile($media->manifest_url, "playlist.m3u8", $mediaPath);
            
                    foreach($mediaFiles as $mediaFile) {
                        $mediaUrl = $prefix.$mediaFile;
                        $this->uploadFile($mediaUrl, $mediaFile, $mediaPath);
                    }
            
                    foreach($m3u8Files as $m3u8File) {
                        $m3u8Url = $prefix.$m3u8File;
                        $this->uploadFile($m3u8Url, $m3u8File, $mediaPath);
                    }

                    $media->update([
                        'manifest_url' => $bunnyUrl,
                        'storage_used' => $fileSize
                    ]);

                    // Update analytics database
                    // $this->analyticsApi->decreaseStorage($media->user_id, $fileSize);
                    $this->analyticsApi->recordStorageUsed($media->user_id, $media->project_id, $media->id, $fileSize);
                }
            }

        } catch(\Exception $exception){
            echo "Message: " . $exception->getMessage() . "\n";
            return [
                'success' => false,
                'message' => $exception->getMessage()
            ];
        }
        
        return [
            'success' => true,
            'message' => 'Done'
        ];
    }

    private function getAllMedias($manifestUrl, $prefix) {
        $mediaFiles = [];
        $m3u8Files = [];

        $fileSize = FileHelper::getRemoteFileSize($manifestUrl); // file size is in KB

        $manifestContent = file_get_contents($manifestUrl);
        $contents_array = explode("\n", $manifestContent);
        
        foreach($contents_array as $line) {
            if(str_ends_with($line, ".m3u8")) {
                array_push($m3u8Files, $line);
            } else if(str_starts_with($line, "#EXT-X-MEDIA:TYPE=AUDIO")){
                $temp = explode("URI=", $line);
                array_push($m3u8Files, str_replace('"', '', $temp[1]));
            }
        }
        
        foreach($m3u8Files as $m3u8File) {
            $m3u8FilePath = $prefix.$m3u8File;
            $fileSize += FileHelper::getRemoteFileSize($m3u8FilePath); // file size is in KB

            $content = file_get_contents($m3u8FilePath);
            $contents = explode("\n", $content);

            foreach($contents as $line) {
                if(str_ends_with($line, ".ts")) {
                    array_push($mediaFiles, $line);
                    $fileSize += FileHelper::getRemoteFileSize($prefix.$line); // file size is in KB
                }
            }
        }
        return [$m3u8Files, $mediaFiles, $fileSize];
    }

    private function uploadFile($url, $filename, $mediaPath) {
        $extension = getExtension($url);
        $file = file_get_contents($url);
        $bunnyUrl = $this->adapter->uploadFile($file, $extension, $filename, $mediaPath);
        echo "Uploaded ".$url."\n";
        return $bunnyUrl;
    }

    private function checkUrlForMigration($url) {
        if($url && (
            str_starts_with($url, 'https://swiftcdn6.global.ssl.fastly.net') || 
            str_starts_with($url, 'https://interactr-uploads.s3.us-east-2.amazonaws.com') || 
            str_starts_with($url, 'https://s3.us-east-2.amazonaws.com/static.videosuite.io') ||
            str_starts_with($url, 'https://s3.amazonaws.com/interactr-2-source-plppvsc270ey') ||
            str_starts_with($url, 'https://s3.us-east-2.amazonaws.com/thumbs.swiftcdn.co'))
        ) {
            return true;
        }
        return false;
    }

    private function checkStorage($userId, $fileSize) {        
        $isStorageLeft = $this->analyticsApi->isStorageLeft($userId, $fileSize);
        if(! $isStorageLeft) {
            throw new \Exception('Not enough storage left!');
        }
    }
}
