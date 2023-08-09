<?php

namespace App\Jobs;

use App\BunnyCdnVideo;
use App\Lib\FileHelper;
use App\Media;
use App\Repositories\MediaRepository;
use App\Services\BunnyCDN\BunnyEdgeStorageAdapter;
use App\Services\AnalyticsApi;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class UploadVideo implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $media;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Media $media)
    {
        //
        $this->media = $media;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $adapter = new BunnyEdgeStorageAdapter();

        $extension = getExtension($this->media->temp_storage_url);

        $file = file_get_contents($this->media->temp_storage_url);
        
        $url = $adapter->uploadVideo($file, $extension);
        
        $fileSize = FileHelper::getRemoteFileSize($url); // file size is in KB
        
        Media::syncUpdateWithFe($this->media, [
            'temp_storage_url' => '',
            'url' => $url,
            'storage_used' => $fileSize
        ]);

        // Update analytics database
        $analyticsApi = new AnalyticsApi();
        // $analyticsApi->decreaseStorage($this->media->user_id, $fileSize);
        $analyticsApi->recordStorageUsed($this->media->user_id, $this->media->project_id, $this->media->id, $fileSize);
    }
}
