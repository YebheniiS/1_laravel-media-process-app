<?php
namespace App\Lib;

use App\Lib\Vendor\GetResponse;

class GetResponseApi {
    protected $email;
    protected $key;
    protected $getResponse;
    protected $campaign;
    protected $name;

    public function __construct($keys)
    {
        $this->key = $keys['key'];
        $this->getresponse = new GetResponse($this->key);
        return $this;
    }

    public function email($email)
    {
        $this->email = $email;
        return $this;
    }

    public function name($name) {
        $this->name = $name;
        return $this;
    }

    public function addToCampaign($campaignId)
    {
        $data = array('success' => false);

        if ($campaignId) {
            $this->campaign = $campaignId;
            $request = $this->getresponse->addContact( $this->createNewContact() );
            if (isset($request->httpStatus)) {
                //dd($request);
                if ($request->message == 'Contact already added') {
                    $data['message'] = 'Contact already added.';
                }else {
                    $data['message'] = 'Error posting to API.';
                }
            } else {
                $data['success'] = true;
                $data['message'] = 'Contact successfully added.';
            }
        } else {
            $data['message'] = 'No Campaign Id';
        }

        return $data;
    }

    private function createNewContact()
    {
        $contact = [
            'email' => $this->email,
            'campaign' => ['campaignId' => $this->campaign],
        ];

        if ($this->name){
            $contact['name'] = $this->name;
        }

        return $contact;
    }

    /**
     * @return false|string
     * @throws \Exception
     */
    public function getCampaigns()
    {
        $campaigns = $this->getresponse->getCampaigns();

        if (property_exists($campaigns,'httpStatus') && $campaigns->httpStatus === 401) {
            throw new \Exception('Authentication Failed.');
        }

        return json_encode(get_object_vars($campaigns));
    }
}