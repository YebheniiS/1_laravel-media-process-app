<?php


namespace App\Services\BunnyCDN;

use App\BunnyCdnVideo;
use GuzzleHttp\Client;

/**
 * Class BunnyStreamAdapter
 * @package App\Services\BunnyCDN
 *
 * Responsible for interacting with the bunny stream api
 */
class BunnyStreamAdapter extends BunnyAdapter
{
    protected $libraryId;
    protected $password;
    protected $cdn;


    /**
     * Create a video in the bunny cdn. We need to do this before
     * we can upload a video
     *
     * @param $name
     * @param $resolution
     * @throws \Exception
     */
    public function createVideo($name, $resolution, $collectionId)
    {
        if(! $resolution) {
            throw new \Exception('No resolution passed to the createVideo method on the BunnyStreamAdapter');
        }

        $this->setConfig($resolution);

        $this->response = $this->request($this->password)
            ->post($this->pathBuilder(), [
                'title' => $name,
                'collectionId' => $collectionId
            ]);
    }

    public function getVideoId()
    {
        $json =  $this->response->json();

        return $json['guid'];
    }

    public function uploadVideo($videoId, $resolution, $file, $extension)
    {
        if(! $resolution) {
            throw new \Exception('No resolution passed to the uploadVideo method on the BunnyStreamAdapter');
        }

        $this->setConfig($resolution);

        $client = new Client();

        $response = $client->request('PUT', 'https://video.bunnycdn.com/library/'.$this->libraryId.'/videos/'.$videoId, [
            'headers' => [
                'AccessKey' => $this->password,
                'accept' => 'application/json',
            ],
            'body' => $file
        ]);

        $result = json_decode($response->getBody());
        if($result) {
            if( ! $result->success ) {
                throw new \Exception($result->message);
            }
        }
        return true;
        // Guzzle needs the full MIME type of the body of the request
        // $mimeType = MIME_TYPES[$extension];

        // try {
        //     $this->response = $this->request($this->password)
        //         ->withBody($file, $mimeType)
        //         ->put($this->pathBuilder($videoId));

        // }catch(\Exception $exception){
        //     echo $exception->getMessage();
        // }
        
        // This method checks the response is OK and throws and exception if any errors with the message
        // passed in
        // $this->checkResponse("Unable to upload file");
    }

    /**
     * Using the resolution as the key we can get and set all the confiig keys / passwords needed
     * for working with the api.
     *
     * @param $resolution
     * @throws \Exception
     */
    private function setConfig($resolution)
    {
        switch($resolution){
            case('1080') :
                $this->libraryId =  config('bunnyCdn.stream_api.1080.id');
                $this->password = config('bunnyCdn.stream_api.1080.password');
                $this->cdn = config('bunnyCdn.stream_api.1080.cdn');
                return;

            case('2160') :
                $this->libraryId =  config('bunnyCdn.stream_api.2160.id');
                $this->password = config('bunnyCdn.stream_api.2160.password');
                $this->cdn = config('bunnyCdn.stream_api.2160.cdn');
                return;

            case('480') :
                $this->libraryId =  config('bunnyCdn.stream_api.480.id');
                $this->password = config('bunnyCdn.stream_api.480.password');
                $this->cdn = config('bunnyCdn.stream_api.480.cdn');
                return;

            case('720') :
                $this->libraryId =  config('bunnyCdn.stream_api.720.id');
                $this->password = config('bunnyCdn.stream_api.720.password');
                $this->cdn = config('bunnyCdn.stream_api.720.cdn');
                return;

        }

        throw new \Exception('Invalid resolution passed to the bunny cdn config setter: ' . $resolution);
    }

    /**
     * Create the path we need to upload to
     *
     * @param null $videoId
     * @return string
     */
    private function pathBuilder($videoId = null)
    {
        // Basic path that all other paths start with
        $path = "https://video.bunnycdn.com/library/$this->libraryId/videos";

        // If no video id we don;t need to add anything else to the path
        if(! $videoId) return $path;

        // To upload to a created video in bunny we need to add the bunny video id to the path
        return $path . '/' . $videoId;
    }

    public function getManifest(BunnyCdnVideo $video)
    {
        $this->setConfig($video->media->encoded_size);

        return 'https://' . $this->cdn . '.b-cdn.net/' . $video->bunny_cdn_video_id . '/playlist.m3u8';
    }

    public function getVideo($videoId, $resolution)
    {
        $this->setConfig($resolution);

        $this->response = $this->request($this->password)->get($this->pathBuilder($videoId));

        return $this->response->json();

    }
}