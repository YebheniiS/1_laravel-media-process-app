<?php

namespace App\Jobs;

use App\Media;
use App\BunnyCdnVideo;
use App\Repositories\BunnyCdnVideoRepository;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ReencodeVideo implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $media;
    protected $bunnyCdnVideo;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Media $media, BunnyCdnVideo $bunnyCdnVideo)
    {
        //
        $this->media = $media;
        $this->bunnyCdnVideo = $bunnyCdnVideo;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        //
        $repo = new BunnyCdnVideoRepository();
        $repo->recreateVideo($this->media, $this->bunnyCdnVideo);
        echo "DONE";
    }
}
