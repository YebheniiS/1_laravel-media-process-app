<?php

namespace App\lib;


use Illuminate\Support\Facades\Storage;
use Restpack\Screenshot;
use App\Services\BunnyCDN\BunnyEdgeStorageAdapter;

class ScreenshotAPI
{
    private $restpackAPI;
    private $content;
    private $bunnyAdapter;
    /**
     * ScreenshotAPI constructor.
     */
    public function __construct()
    {
        $this->restpackAPI = new Screenshot(
          config('services.restpack.ACCESS_TOKEN')
        );
        $this->bunnyAdapter = new BunnyEdgeStorageAdapter();
    }

    public function make($page_url, $format)
    {
        $this->content = $this->restpackAPI->captureToImage($page_url, ['format' => $format, 'thumbnail_width' => 740, 'thumbnail_height' => 810, 'delay' => '10000']);

        return $this;
    }

    public function uploadToBunnyCDN()
    {
        $url = $this->bunnyAdapter->uploadImage($this->content, 'png');
        return $url;
    }
}