<?php


namespace App\Lib;


use App\Media;
use Illuminate\Support\Facades\Storage;

class PreloadConverter
{
    protected $result;
    protected $manifest;

    protected const FILENAME = 'preload_manifest.m3u8';

    public function __construct($manifest = null)
    {
        $this->manifest = $manifest;
//        $this->createPreloadManifest();
//        $this->pushManifestToStorage();
    }

    public function getResult()
    {
        return $this->result;
    }

    protected function createPreloadManifest()
    {
        $last_line = "#EXT-X-ENDLIST";

        $contents_array = explode("\n", $this->getOriginalManifest());

        $firstVideoFound = false;

        foreach ($contents_array as $key => $line) {
            if (strpos($line, "video_1/segment-0.ts") !== false) {
                $firstVideoFound = true;
            } else {
                // if we have found the correct line, delete all below lines apart from the last line
                if ($firstVideoFound) {
                    unset($contents_array[$key]);
                }
            }
        }

        $contents_array[] = $last_line;
        $this->manifest = join("\n", $contents_array);
    }

    protected function getOriginalManifest()
    {
        return file_get_contents($this->manifest);
    }

    protected function pushManifestToStorage()
    {

    }
}