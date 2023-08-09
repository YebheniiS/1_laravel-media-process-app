<?php


namespace App\Lib\Media;

class MediaEncodedEraser extends BaseMediaEraser
{

    public function operation()
    {
        $url = $this->getEncodedURL();

        $deleteFromS3 = str_contains($url, $this->domainToMatch);

        if ($deleteFromS3) {
            $urlKey = $this->getS3KeyFromURL($url, '/playlist.m3u8');

            $this->pushS3DirectoryKey($urlKey);

            $this->pushIfMatchThumbnail();

            $this->deleteS3Objects();
        }
    }

    public function pushIfMatchThumbnail()
    {
        $thumbnail_url = $this->getThumbnailURL();

        if (!empty($thumbnail_url) && str_contains($thumbnail_url, '/thumbnails/')) {
            $thumbnailKey = $this->getS3KeyFromURL($thumbnail_url, '/0.jpg');

            $this->pushS3DirectoryKey($thumbnailKey);
        }
    }
}