<?php

namespace App\Http\Controllers;

use App\Services\FineUploader;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;


class ImageController extends Controller
{
    protected $fineUploader;

    public function __construct(FineUploader $fineUploader) {
        $this->fineUploader = $fineUploader;
    }

    public function upload(Request $request)
    {
        $imageUrl =  $this->fineUploader->handleUpload($request->get('s3FilePath'));
        try {
            list($width, $height) = getimagesize($imageUrl);

            // Check the image is smaller than the canvas
            if ($width > 700) {
                $ratio = $width / 700;
                $width = 700;
                $height = $height / $ratio;
            }

            // Check after scailing the height is not too big
            if($height > 393){
                $ratio = $height / 393;
                $height = 393;
                $width = $width / $ratio;
            }

        }catch(\Exception $exception){
            // if this failts to get image size still allow FE to proceed
            $height = '100px';
            $width = '100px';
        }




        return ['src' => $imageUrl, 'success' => true, 'height'=>$height, 'width'=>$width];
    }
}
