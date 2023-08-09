<?php
namespace App\Lib;

use GuzzleHttp\Client;

class SendLaneApi
{
    protected $email;
    protected $key;
    protected $hash;
    protected $domain;
    protected $listId;
    protected $client;
    protected $fname;
    protected $sname;

    public function __construct($keys)
    {
        $this->key = $keys['key'];
        $this->hash = $keys['hash'];
        $this->domain = $keys['domain'];
        $this->client = new Client();
        return $this;
    }

    /**
     * @return mixed
     * @throws \Exception
     */
    public function getLists()
    {
        $request = $this->newRequest('/lists');
        $contents = json_decode($request->getBody()->getContents());

        if (! is_array($contents) && property_exists($contents, 'error')) {
            throw new \Exception('Authorization Failed.');
        }

        return  $contents;
    }

    public function email($email)
    {
        $this->email = $email;
        return $this;
    }

    /**
     * Active Campaign makes this a bitch by using 2 name fields. We explode on empty
     * space but we have to account for users who have more that one space in the name
     * so on second name we implode the array back to a string after removing the first
     * index (first name)
     *
     * @param $name
     * @return $this
     */
    public function name($name) {
        $splitName = explode(' ', $name);
        $this->fname = $splitName[0];
        unset($splitName[0]);
        $this->sname = implode(' ', $splitName);

        return $this;
    }

    public function addContact($listId)
    {
        $newContact = $this->createContact();
        $newContact['list_id'] = $listId;

        $request = $this->newRequest('/list-subscriber-add', $newContact);
        if ($request->getBody()->__toString() == '{"success":"Subscriber added successfully"}') {
            return;
        } else {
            throw new \Exception($request->getBody()->__toString() );
        }
    }

    private function createContact(){
        $contact = [
            'email' => $this->email,
        ];

        if ($this->fname) {
            $contact['first_name'] = $this->fname;
        }

        if ($this->sname) {
            $contact['last_name'] = $this->sname;
        }

        return $contact;
    }

    private function newRequest($url, $data = [])
    {
        return $this->client->post($this->url($url), [
            'form_params' => array_merge($this->keys(), $data)
        ]);
    }

    private function url($url) {
        return $this->domain . '/api/v1' . $url;
    }

    private function keys()
    {
        return [
            'api' => $this->key,
            'hash' => $this->hash
        ];
    }
}