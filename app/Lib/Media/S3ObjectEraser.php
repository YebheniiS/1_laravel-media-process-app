<?php


namespace App\Lib\Media;


class S3ObjectEraser extends BaseMediaEraser
{
    protected $encodedPartsToRemove = ['hls/', 'thumbnails/', '/playlist.m3u8', '/0.jpg'];

    public function operation() {}


    public function checkThenPushKeyToErase($key)
    {
        if (! $this->fileInUser($key)) {

            /**
             * If given object key is standard file, simply delete it
             */
            if ($this->isStandardFile($key)) {
                $this->pushS3FileKey($key);

                return true;
            }

            /**
             * If given object key is encoded file, delete directories from "hls" and "thumbnails" folders
             */
            if ($this->isEncodedFile($key)) {
                $cleanedKey = $key;

                foreach ($this->encodedPartsToRemove as $part) {
                    $cleanedKey = str_replace($part, '', $cleanedKey);
                }

                $this->pushS3DirectoryKey("hls/$cleanedKey");
                $this->pushS3DirectoryKey("thumbnails/$cleanedKey");

                return true;
            }
        }
    }

    public function fileInUser($key)
    {
        $media = $this->media->where('manifest_url', 'LIKE', "%$key%")
                                ->orWhere('url', 'LIKE', "%$key%")
                                ->orWhere('thumbnail_url', 'LIKE', "%$key%")
                                ->orWhere('compressed_url', 'LIKE', "%$key%")
                                ->orWhere('compressed_mp4', 'LIKE', "%$key%")
                                ->first();

        return !is_null($media);
    }

    public function isEncodedFile($key)
    {
        return str_contains($key, 'hls/') || str_contains($key, 'thumbnails/');
    }

    public function isStandardFile($key)
    {
        return str_contains($key, 'media/') || str_contains($key, 'thumbs.swiftcdn.co/');
    }
}