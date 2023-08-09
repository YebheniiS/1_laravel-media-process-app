<?php

namespace App\Http\Controllers;

use App\Services\BunnyCDN\BunnyEdgeStorageAdapter;
use Illuminate\Http\Request;
use App\Services\FineUploader;
use Illuminate\Support\Facades\Storage;

class FileController extends Controller
{
    protected $adapter;

    public function __construct()
    {
        $this->adapter =  new BunnyEdgeStorageAdapter();
    }

    public function uploadToStream()
    {

    }

    public function uploadBase64()
    {
        $base64 = request()->base64String;

        $image_parts = explode(";base64,", $base64);
        $image_type_aux = explode("image/", $image_parts[0]);
        $image_type = $image_type_aux[1];
        $image_base64 = base64_decode($image_parts[1]);

        $file = $this->adapter->uploadImage(
            $image_base64, $image_type
        );

        return [
            'url' => $file
        ];
    }

    public function uploadImage()
    {
        $file =  $this->adapter->uploadImage(
            request()->file('file')->get(),
            request()->file('file')->extension()
        );

        return $file;
    }

    public function deleteImage($url)
    {
        return $this->adapter->deleteImage($url);
    }
}
