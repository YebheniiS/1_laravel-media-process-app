<?php


namespace App\Services\BunnyCDN;


use Illuminate\Support\Facades\Http;

class BunnyEdgeStorageAdapter extends BunnyAdapter
{
    protected $url; // Final URL of the uploaded file
    protected $zone;
    protected $password;
    protected $cdn;


    /**
     * Upload a new item to the user uploads folder
     *
     * @param $data
     * @param $filetype
     * @return mixed
     */
    public function uploadVideo($data,  $filetype, $projectId=null)
    {
        $this->setConfig('videos');

        // Create a unique filename for the file and get the files type from it's contents.
        // This is guessed by laravel so could be different from the
        // type uploaded. See here for more info  https://laravel.com/docs/master/requests#retrieving-uploaded-files
        $filename = guid() . '.' . $filetype;

        // Guzzle needs the full MIME type of the body of the request
        $mimeType =   MIME_TYPES[$filetype];

        // Perform the upload (errors are catched in the upload method)
        $this->upload(
            $filename,
            $mimeType,
            $data,
            $projectId
        );

        return $this->url;
    }

    public function uploadFile($data,  $filetype, $filePath, $projectId)
    {
        $this->setConfig('videos');

        // Create a unique filename for the file and get the files type from it's contents.
        // This is guessed by laravel so could be different from the
        // type uploaded. See here for more info  https://laravel.com/docs/master/requests#retrieving-uploaded-files
        $filename = guid() . '.' . $filetype;
        // Guzzle needs the full MIME type of the body of the request

        $mimeType =  $filetype == "ts" ? "video/mp2t" : ($filetype == "m3u8" ? "text/plain" : MIME_TYPES[$filetype]);

        // Perform the upload (errors are catched in the upload method)
        $this->upload(
            $filePath,
            $mimeType,
            $data,
            $projectId
        );

        return $this->url;
    }

    public function deleteVideo($url)
    {
        $this->setConfig('video');

        $array = explode("/", $url );
        $filename =  last($array);

        return $this->delete($filename);
    }


    public function uploadImage($data, $filetype)
    {
        $this->setConfig('images');

        // Create a unique filename for the file and get the files type from it's contents.
        // This is guessed by laravel so could be different from the
        // type uploaded. See here for more info  https://laravel.com/docs/master/requests#retrieving-uploaded-files
        $filename = guid() . '.' . $filetype;

        // Guzzle needs the full MIME type of the body of the request
        $mimeType =   MIME_TYPES[$filetype];

        // Perform the upload (errors are catched in the upload method)
        $this->upload(
            $filename,
            $mimeType,
            $data
        );

        return $this->url;
    }

    public function deleteImage($url)
    {
        $this->setConfig('images');

        // The path to upload the file into
        $path = auth()->check() ? auth()->id() : "0";

        $array = explode("/", $url );
        $filename =  last($array);

        return $this->delete($path, $filename);
    }


    public function uploadProject($data, $filename)
    {
        $this->setConfig('projects');

        $mimeType = 'application/javascript';

        $this->upload(
            $filename,
            $mimeType,
            $data
        );

        return $this->url;
    }

    public function deleteProject($project)
    {
        $this->setConfig('projects');

        // https://p-fast.b-cdn.net/l/1/projects/5e6710c012911/index.html

        $path = str_replace("https://$this->cdn.b-cdn.net/", "", $project->published_path);

        return $this->delete(
            $path
        );
    }


    /**
     * Upload a file to storage. The zone
     * is defined by the API key passed in when the
     * class is initialised
     * @param $zone
     * @param $accessKey
     * @param $path
     * @param $filename
     * @param $mimeType
     * @param $data
     * @return string
     */
    private function upload($filename, $mimeType, $data, $projectId=null)
    {
        $this->response  = $this->request($this->password)
            ->withBody($data, $mimeType)
            ->put($this->pathBuilder($this->getPath($projectId), $filename));

        $this->checkResponse("Unable to upload file");

        $this->saveUrl($this->getPath($projectId) . "/" . $filename);
    }


    /**
     * Delete and object from bunny CDN storage
     * @param $accessKey
     * @param $zone
     * @param $path
     * @param $filename
     */
    private function delete($path)
    {
        $this->response = $this->request($this->password)
            ->delete($this->pathBuilder($path));
        
        $this->checkResponse("Unable to delete file");

        return 'success';
    }


    /**
     * Build the path to the item in storage for uploading a new
     * item or deleting an old one
     *
     * @param $zone
     * @param $path
     * @param $filename
     * @return string
     */
    private function pathBuilder($path, $filename = null)
    {
        $path =  "https://storage.bunnycdn.com/$this->zone/$path";

        if($filename) {
            $path .= "/$filename";
        }

        return $path;
    }


    /**
     * Receives the CDN for the storage zone and the file path and
     * saves the full URL
     *
     * @param $cdn
     * @param $filepath
     * @return string
     */
    private function saveUrl($filepath) {
        $this->url =  "https://$this->cdn.b-cdn.net/$filepath";
    }

    /**
     * Set all config keys for the api
     *
     * @param $zone
     * @throws \Exception
     */
    private function setConfig($zone)
    {
        switch($zone){
            case('images') :
                $this->zone =  config('bunnyCdn.storage_api.images.zone');
                $this->password = config('bunnyCdn.storage_api.images.passwword');
                $this->cdn = config('bunnyCdn.storage_api.images.cdn');
                return;

            case('videos') :
                $this->zone =  config('bunnyCdn.storage_api.videos.zone');
                $this->password = config('bunnyCdn.storage_api.videos.passwword');
                $this->cdn = config('bunnyCdn.storage_api.videos.cdn');
                return;

            case('projects') :
                $this->zone =  config('bunnyCdn.storage_api.projects.zone');
                $this->password = config('bunnyCdn.storage_api.projects.passwword');
                $this->cdn = config('bunnyCdn.storage_api.projects.cdn');
                return;

        }

        throw new \Exception('Invalid zone passed to the bunny cdn config setter');
    }

    private function getPath($projectId = null)
    {
        // return  substr(env('APP_ENV'), 0, 1) . "/" . ($projectId ? $projectId : auth()->user()->id) ; 
        return  substr(env('APP_ENV'), 0, 1) . ($projectId ? '/' . $projectId : ''); // removed user_id from project path when publishing
    }

    public function purge($url)
    {
        // Purge uses the account api so we don't use the set confiig method here
        $this->response = $this->request('bff0a3ae-8afe-463b-bc0b-197e61be85a4b280f76a-ca53-4d75-80e3-66fcb9fb5b47')
            ->get('https://api.bunny.net/purge?url='.$url);


        //
        //$this->checkResponse('Unable to purge cdn cache');

    }
}