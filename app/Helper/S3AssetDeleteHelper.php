<?php

namespace App\Helper;

use App\Media;
use App\Repositories\MediaRepository;
use Illuminate\Filesystem\FilesystemAdapter;
use Illuminate\Support\Facades\Storage;

class S3AssetDeleteHelper
{
    /* @var FilesystemAdapter Storage */
    protected $storage;
    /**
     * @var MediaRepository
     */
    protected $mediaRepository;
    private $disk = 's3';

    public function __construct()
    {
        $this->storage = Storage::disk($this->disk);
        $this->mediaRepository = app()->make(MediaRepository::class);
    }

    public function setStorageDisk($disk)
    {
        $this->storage = Storage::disk($disk);
    }

    public function delete($s3Key, $directory = false, $disk = 's3')
    {
        $this->setStorageDisk($disk);

        $matches = Media::hasS3KeyMatches($s3Key);
        if ($matches > 1 || env('APP_ENV') !== 'production') {
            return null;
        }

        if ($this->storage->exists($s3Key)) {
            $deleteMethod = $directory ? 'deleteDirectory' : 'delete';

            if (method_exists($this->storage, $deleteMethod)) {
                return $this->storage->$deleteMethod($s3Key);
            }
        }
    }
}