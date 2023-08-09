<?php
namespace App\Lib;


use AWeberAPI;

require_once('vendor/aweber_api/aweber_api.php');

class AweberApiConnector
{
    protected $email;
    protected $keys;
    protected $aWebber;
    protected $account;
    protected $name;

    public function __construct($keys)
    {
        $this->keys = $keys;
        $this->aWebber =  new AWeberAPI($this->keys['app_key'], $this->keys['app_secret']);
        try {
            $this->account = $this->aWebber->getAccount($this->keys['access_token'],$this->keys['access_token_secret']);
        } catch(\AWeberAPIException $exc) {
            abort('500', $exc->message );
        }

        return $this;
    }

    public function email($email)
    {
        $this->email = $email;
        return $this;
    }

    public function name($name){
        $this->name = $name;
        return $this;
    }

    public function addToList($listId)
    {
        try {
            $contact = $this->createNewContact();
            foreach ($this->account->lists as $list) {
                if($list->id == $listId) {
                    $subscribers = $list->subscribers;
                    return $subscribers->create($contact);
                };
            }
            abort('500', 'List not found');
        } catch(\AWeberAPIException $exc) {
            abort('500', $exc->message );
        }
    }

    private function createNewContact()
    {
        $contact = ["email" => $this->email];

        if  ($this->name) {
            $contact['name'] = $this->name;
        }
        
        return $contact;
    }

    public function getLists()
    {
        $lists = [];
        foreach ($this->account->lists as $list) {
            $lists[] = $list->data;
        }
        return $lists;
    }
}