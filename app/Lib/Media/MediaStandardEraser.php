<?php


namespace App\Lib\Media;

class MediaStandardEraser extends BaseMediaEraser
{
    protected $thumbnailBucket = "/thumbs.swiftcdn.co/";
    protected $compressedBucket = "/cdn6.swiftcdn.co/";

    public function operation()
    {
        $url = $this->getStandardURL();

        $deleteFromS3 = str_contains($url, $this->domainToMatch);

        if ($deleteFromS3) {
            $urlKey = $this->getS3KeyFromURL($url);

            $this->pushS3FileKey($urlKey);

            $this->pushIfMatch('Thumbnail', 'thumbsSwiftCDN');

            $this->pushIfMatch('Compressed');

            $this->pushIfMatch('CompressedMp4');

            $this->deleteS3Objects();
        }
    }

    /**
     * If given type url exist in S3, remove it
     *
     * @param $type
     * @param string $disk
     */
    public function pushIfMatch($type, $disk = 's3')
    {
        $method = "get{$type}URL";
        $url = $this->$method();

        $needles = $type === 'Thumbnail' ? $this->thumbnailBucket : $this->compressedBucket;

        if (!empty($url) && str_contains($url, $needles)) {
            $key = $this->getS3KeyFromURL($url, $needles);

            $this->pushS3FileKey($key, false, $disk);
        }
    }

}