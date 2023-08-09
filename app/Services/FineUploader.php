<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Storage;

class FineUploader
{
    private $disk = 's3';
    /* @var FilesystemAdapter Storage */
    private $storage;

    public function __construct()
    {
        $this->storage = Storage::disk($this->disk);
    }

    // copy from file uploader public temp bucket to actual bucket.
    public function handleUpload($s3FilePath)
    {
//        if( Auth::user()->is_club && Auth::user()->should_stream_videos  ){
//
//            $stream = Storage::disk('publicUploads')->getDriver()->readStream( $request->get('s3FilePath'));
//            $filePath = str_replace('temp/media/', '', $request->get('s3FilePath'));
//            Storage::disk('streamSource')->put($filePath, $stream,  'public');
//
//            return 'https://s3.amazonaws.com/interactr-2-source-plppvsc270ey/' . $filePath;
//
//        } else {
            $s3CurrentKey = str_replace('temp/', '', $s3FilePath);
            $publicUploadBucket = config('filesystems.disks.publicUploads.bucket');

            $response = $this->storage->getDriver()->getAdapter()->getClient()->copyObject([
                'Bucket' =>  env('AWS_BUCKET'),
                'Key' => $s3CurrentKey,
                'CopySource' => $publicUploadBucket . '/' . $s3FilePath,
                'ACL' => 'public-read'
            ]);

            return $response->get('ObjectURL');
        //}
    }
}
