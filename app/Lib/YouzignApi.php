<?php


namespace App\Lib;


use GuzzleHttp\Client;

class YouzignApi
{
    const YOUZIGN_API_URL = 'https://www.youzign.com/api';
    protected $key;
    protected $token;
    protected $client;

    public function __construct($keys)
    {
        $this->key = $keys['key'];
        $this->token = $keys['hash'];
        $this->client = new Client();
        return $this;
    }

    /**
     * @return mixed
     * @throws \Exception
     */
    public function authenticate()
    {
        $request = $this->request();
        $contents = json_decode($request->getBody()->getContents());

        if (property_exists($contents, 'error')) {
            throw new \Exception('Authorization Failed.');
        }

        if (property_exists($contents, 'authenticated'))
            return  $contents;
    }


    private function request($url = self::YOUZIGN_API_URL)
    {
        return $this->client->post($url, [
            'form_params' => $this->keys()
        ]);
    }

    private function keys()
    {
        return [
            'key' => $this->key,
            'token' => $this->token
        ];
    }

}