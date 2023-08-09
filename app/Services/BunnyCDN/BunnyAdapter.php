<?php


namespace App\Services\BunnyCDN;


use Illuminate\Support\Facades\Http;

class BunnyAdapter
{
    protected $response;

    /**
     * return the response object
     *
     * @return mixed
     */
    public function getResponse()
    {
        return $this->response;
    }

    /**
     * Format the response as a JSON object
     *
     * @return mixed
     */
    public function getJSON()
    {
        return $this->response->json();
    }

    /**
     * Create a new request with the required headers
     *
     * @return \Illuminate\Http\Client\PendingRequest
     */
    public function request($accessKey)
    {
       return Http::withHeaders([
            'accessKey' => $accessKey
        ])->timeout(0);
    }

    /**
     * Check the request was a success
     *
     * @param string $error
     * @return bool
     * @throws \Exception
     */
    public function checkResponse($error = 'Error')
    {

        if( ! $this->response->successful() ) {
            throw new \Exception($error);
        }

        return true;
    }
}