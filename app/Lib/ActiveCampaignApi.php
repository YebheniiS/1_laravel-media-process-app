<?php
namespace App\Lib;


class ActiveCampaignApi {
    protected $email;
    protected $keys;
    protected $ac;
    protected $list;
    protected $fname;
    protected $sname;

    public function __construct($keys)
    {
        $this->keys = $keys;
        $this->ac = new \ActiveCampaign($this->keys['url'], $this->keys['key']);

        if (!(int)$this->ac->credentials_test()) {
            throw new \Exception('Invalid credentials (URL and/or API key)');
        }

        return $this;
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

    public function addToList($listId)
    {
        $this->list = $listId;
        $contact = $this->createNewContact();
        $contact_sync = $this->ac->api("contact/sync", $contact);

        if (!(int)$contact_sync->success) {
            throw new \Exception("<p>Syncing contact failed. Error returned: " . $contact_sync->error . "</p>");
        }

        return;
    }

    private function createNewContact()
    {
        $contact = [
            "email" => $this->email,
            "p[{$this->list}]" => $this->list,
        ];

        if ($this->fname) {
            $contact['first_name'] = $this->fname;
        }

        if ($this->sname) {
            $contact['last_name'] = $this->sname;
        }

        return $contact;
    }

    public function getLists()
    {
        $res = $this->ac->api('list/list?ids=all');
        $lists = array_filter(get_object_vars($res), function($key){
            return is_numeric($key);
        }, ARRAY_FILTER_USE_KEY);
////        foreach($res as $key => $val) {
////            if (!is_numeric($key)) {
////                unset($res[$key]);
////            }
//        }

        return json_encode($lists);
    }
}