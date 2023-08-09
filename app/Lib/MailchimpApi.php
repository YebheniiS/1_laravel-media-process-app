<?php

namespace App\Lib;

use DrewM\MailChimp\MailChimp;

class MailchimpApi
{
    protected $email;
    protected $keys;
    protected $mailchimp_api;
    protected $list;
    protected $name;

    public function __construct($data)
    {
        try {
            $this->mailchimp_api = new MailChimp($data['key']);
            return $this;
        } catch (\Exception $e) {
            return null;
        }
    }

    public function email($email)
    {
        $this->email = $email;
        return $this;
    }

    public function name($name)
    {
        $this->name = $name;
        return $this;
    }

    public function addToList($listId)
    {
        $this->list = $listId;
        $data = [
            'email_address' => $this->email,
            'status' => 'subscribed',
        ];
        // append name if available
        if($this->name) {
                $data['merge_fields'] =  [
                'FNAME' => $this->name
            ];
        }

        $contact_sync = $this->mailchimp_api->post("lists/$listId/members", $data);

        if($contact_sync['status'] === 400) {
            throw new \Exception($contact_sync['title']);
        }

        return $contact_sync;
    }

    public function getLists()
    {
        try {
            if (! $this->mailchimp_api) return false;

            return $this->mailchimp_api->get('lists');
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }
    }
}