<?php


namespace App\Lib\Media;


use App\Helper\S3AssetDeleteHelper;
use App\Media;

abstract class BaseMediaEraser
{
    protected $media;
    protected $s3DeleteHelper;
    protected $domainToMatch = "swiftcdn6.global.ssl.fastly.net";

    protected $s3ObjectKeys = [];

    public function __construct(Media $media)
    {
        $this->media = $media;
        $this->s3DeleteHelper = new S3AssetDeleteHelper();
    }

    /**
     * The MAIN constructor method runs as any Eraser Lib instance created
     * Example. new MediaEncodedEraser(), new MediaStandardEraser() etc.
     *
     * @return mixed
     */
    abstract public function operation();

    protected function getEncodedURL()
    {
        return $this->media->manifest_url;
    }

    /**
     * Get media item 'url'
     *
     * @return mixed
     */
    protected function getStandardURL()
    {
        return $this->media->url;
    }

    /**
     * Get media item 'thumbnail_url'
     *
     * @return mixed
     */
    protected function getThumbnailURL()
    {
        return $this->media->thumbnail_url;
    }

    /**
     * Get media item 'compressed_url'
     *
     * @return mixed
     */
    protected function getCompressedURL()
    {
        return $this->media->compressed_url;
    }

    /**
     * Get media item 'compressed_mp4'
     *
     * @return mixed
     */
    protected function getCompressedMp4URL()
    {
        return $this->media->compressed_mp4;
    }

    /**
     * Search and return S3 object key from url
     *
     * Example.
     * return - media/6023f3a7-9df7-42cf-a3fa-9cef6cd35bfb.mp4
     * from - https://swiftcdn6.global.ssl.fastly.net/media/6023f3a7-9df7-42cf-a3fa-9cef6cd35bfb.mp4
     *
     * @param $url
     * @param null $searchAndRemove
     * @return string
     */

    protected function getS3KeyFromURL($url, $searchAndRemove = null)
    {
        $url_parts = parse_url($url);
        $path = $url_parts['path'];

        if (isset($searchAndRemove)) {
            $path = str_replace($searchAndRemove, '', $path);
        }

        $s3Key = ltrim( $path, '/');

        return $s3Key;
    }

    /**
     * Push given S3 SINGLE file key to delete at the end
     *
     * @param $key
     * @param bool $isDirectory
     * @param string $disk
     */
    public function pushS3FileKey($key, $isDirectory = false, $disk = 's3')
    {
        $this->s3ObjectKeys[] = compact('key', 'isDirectory', 'disk');
    }

    /**
     * Push given S3 FOLDER Object key to delete at the end
     * @param $key
     */
    public function pushS3DirectoryKey($key)
    {
        $this->pushS3FileKey($key, true);
    }

    /**
     *
     * @param Media $media
     * @throws \Exception
     */
    public function eraseMedia()
    {
        $this->operation();
    }

    public function thereIsObjectsToErase()
    {
        return count($this->s3ObjectKeys) > 0;
    }

    /**
     * Delete
     */
    public function deleteS3Objects()
    {
        if (!empty($this->s3ObjectKeys)) {
            foreach ($this->s3ObjectKeys as $s3ObjectKey) {
                $key = $s3ObjectKey['key'];
                $isDirectory = $s3ObjectKey['isDirectory'];
                $disk = $s3ObjectKey['disk'];

                $this->s3DeleteHelper->delete($key, $isDirectory, $disk);
            }
        }
    }
}