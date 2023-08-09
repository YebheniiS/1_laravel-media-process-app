<?php

namespace App\Lib;

use App\Media;
use App\Project;
use App\Repositories\ProjectRepository;
use GuzzleHttp\Client;
use App\Services\BunnyCDN\BunnyEdgeStorageAdapter;

class ThumbnailGenerator
{
    protected $url;
    protected $thumb;
    protected $video;
    protected $adapter;

    public function __construct($url = '')
    {
        $this->url = $url;
    }

    public static function url($url)
    {
        return new self($url);
    }

    public function generate($videoId, $time = null, $responseController = 'media')
    {
        $params = [
            'video' => $this->url,
            'videoId' => $videoId,
            'responseEndpoint' => (isset($_SERVER['HTTPS']) ? "https" : "http") ."://".$_SERVER['HTTP_HOST']."/api/$responseController/dgshdthbdzsgrdhfbvc/generate-thumbnail-complete"
        ];

        if (!is_null($time)) {
            $params['time'] = $time;
        }

        $client = new Client();
        $client->request('POST', 'http://thumbnails.videosuite.io/sregnlkerslgkbeshbgsbgsdsdfgsrg', [
            'form_params' => $params
        ]);

//        $client->request('POST', 'http://localhost:3005/sregnlkerslgkbeshbgsbgsdsdfgsrg', [
//            'form_params' => $params
//        ]);
    }

    public function padThumbnailToSize($thumbData, $newWidth, $newHeight)
    {
    
        $temp_file = tempnam(sys_get_temp_dir(), 'Img');
      
        $thumbLocation = tempnam(sys_get_temp_dir(), 'Thumb');
      
        file_put_contents($thumbLocation, $thumbData);
  
        var_dump(1);
        $dest = @imagecreatetruecolor($newWidth, $newHeight);
        var_dump(2);
        $type = substr($this->url, -3);
        if(in_array($type, ['jpg', 'peg'])){
            $src = imagecreatefromjpeg($thumbLocation);
        } else if(in_array($type, ['png'])){
            $src = imagecreatefrompng($thumbLocation);
        } else if(in_array($type, ['gif'])) {
            $src = imagecreatefromgif($thumbLocation);
        }



        list ($width, $height) = getimagesize($thumbLocation);

        $newSrcHeight = ($height / $width) * $newWidth;

        $topDiff = $newHeight - $newSrcHeight;
        $dstX = $topDiff ? $topDiff / 2 : 0;

        imagecopyresampled($dest, $src, 0, $dstX, 0, 0, $newWidth, $newSrcHeight, $width, $height);
        imagejpeg($dest, $temp_file);

        imagedestroy($dest);
        imagedestroy($src);

        $imageData = file_get_contents($temp_file);
        unlink($temp_file);
        return $imageData;
    }

    public function updateThumbnail($mediaId, $thumbnail){

        // Firstly update the thumbnail on the media db row
        try {
            if (empty($mediaId) || empty($thumbnail)) {
                throw new \Exception("Missing crucial fields for thumbnail generation completion");
            }

            $item = Media::findOrFail($mediaId);
            $item->thumbnail_url = $thumbnail;
            $item->save();
        }catch(\Exception $e){
            return;
        }

        // Now push that change to the FE
        $push = new PushNotification();
        $push->channel('media')->event('update')->push([
            'id' => $mediaId,
            'data' => [
                'thumbnail_url' => $thumbnail
            ]
        ]);


        // Check if this thumbnail should be assigned as the project thumbnail
        try {
            $project = Project::findOrFail($item->project_id);
            if($project->start_node_id === $mediaId) {
                $project->image_url = $thumbnail;
                $project->save();

                $push->channel('project')->event('update')->push([
                    'id' => $project->id,
                    'data' => [
                        'image_url' =>$thumbnail
                    ]
                ]);

                $socialThumbs = $this->generateSocialThumbs($thumbnail);
                foreach ($socialThumbs as $key => $value) {
                    $project->$key = $value;
                }
                $project->save();
            }
        }catch(\Exception $e){
            return;
        }
    }

    public function generateSocialThumbs($url){
     
        $data = file_get_contents($url);
    
    
        $fbThumbData = $this->padThumbnailToSize($data,1200, 630);
     
        $twitterThumbData = $this->padThumbnailToSize($data,1200, 600);
      
        $googleThumbData = $this->padThumbnailToSize($data,1080, 608);
    

        $id = strtolower( str_replace( ' ', '-', str_random(32) ) ) .time(). '.png';
     
        
        $this->storage = new BunnyEdgeStorageAdapter();
      


        $fbUrl = $this->storage->uploadImage($fbThumbData, 'png');
        $twitterUrl = $this->storage->uploadImage($twitterThumbData, 'png');
        $googleUrl = $this->storage->uploadImage($googleThumbData, 'png');
        
        return [
            'facebook_image_url' => $fbUrl,
            'twitter_image_url' => $twitterUrl,
            'google_image_url' => $googleUrl
        ];
    }
}
