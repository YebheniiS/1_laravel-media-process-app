<?php

namespace App\Jobs;

use App\Lib\Media\S3ObjectEraser;
use App\Lib\S3ClientHelper;
use App\Media;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class DeleteOldS3FilesJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $s3Eraser;
    protected $s3ClientHelper;
    protected $s3ObjectEraser;

    /**
     * Create a new job instance.
     *
     * DeleteOldS3FilesJob constructor.
     */
    public function __construct()
    {
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $this->s3ClientHelper = new S3ClientHelper();
        $this->s3ObjectEraser = new S3ObjectEraser(new Media());


        $bucketObjects = $this->s3ClientHelper->getBucketObjects();

        if (isset($bucketObjects)) {
            $bucketObjects->loop(function ($key) {
                $this->s3ObjectEraser->checkThenPushKeyToErase($key);
            });

            if ($this->s3ObjectEraser->thereIsObjectsToErase()) {
                $this->s3ObjectEraser->deleteS3Objects();
            }
        }

    }
}
