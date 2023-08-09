<?php

namespace App\Repositories;

require_once(__DIR__.'/../Lib/vendor/aweber_api/aweber_api.php');

use App\Lib\ActiveCampaignApi;
use App\Lib\GetResponseApi;
use App\Lib\MailchimpApi;
use App\Lib\SendLaneApi;
use App\Lib\YouzignApi;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class UserRepository {
    /**
     * Is the authenticated user allowed to login as the
     * request user id
     *
     * @param $userId
     * @return bool
     * @throws \Exception
     */
    public function canLoginAsThisUser($userId){
        if (Auth::user()->superuser === 1){
            return true;
        }
        $user = User::query()->findOrFail($userId);
        if($user->parent_user_id === Auth::user()->id) {
            return true;
        }

        /** Throw an exception that can be picked up by the exception handler */
        throw new \Exception('Unauthorised');
    }

    /**
     * Login as a new user
     *
     * @param $userId
     * @return |null
     * @throws \Exception
     */
    public function loginAsUser($userId){

        $this->canLoginAsThisUser($userId);

        /** Force logout of current user */
        Auth::user()->tokens()->delete();

        /** Login the new user */
        $user = User::query()->findOrFail($userId);
        Auth::guard('web')->loginUsingId($userId);        
        // all good so return the token
        $token = $user->createToken("interactr");
        
        return $token->plainTextToken;
    }


    /**
     * Validate Youzign API keys
     * @param $data
     * @return mixed
     * @throws \Exception
     */
    public function checkYouzignAPIKeys($data)
    {
        $youzignApi = new YouzignApi($data);

        return $youzignApi->authenticate();
    }

    /**
     * Validate Sendlane API keys
     *
     * @param $data
     * @return array|mixed
     * @throws \Exception
     */
    public function getSendlaneLists($data)
    {
        $sendlaneApi = new SendLaneApi($data);

        return $sendlaneApi->getLists();
    }

    /**
     * Validate Mailchimp API keys
     *
     * @param $data
     * @return array|bool|false
     * @throws \Exception
     */
    public function getMailchimpLists($data)
    {
        $mailchimpApi = new MailchimpApi($data);
        if ( !$mailchimpApi instanceof MailchimpApi) return false;

        return $mailchimpApi->getLists();
    }

    /**
     * Validate Active Campaign API keys
     *
     * @param $data
     * @return mixed
     * @throws \Exception
     */
    public function getActiveCampaigns($data)
    {
        $activeCampaign = new ActiveCampaignApi($data);
        $campaignLists = $activeCampaign->getLists();

        return json_decode($campaignLists);
    }

    /**
     * Validate Get Response API keys
     *
     * @param $data
     * @return false|string
     * @throws \Exception
     */
    public function getGetResponseLists($data)
    {
        $getResponseAPI = new GetResponseApi($data);
        return $getResponseAPI->getCampaigns();
    }

    /**
     * Validate Awebber API token
     *
     * @param $token
     * @return array
     * @throws \Exception
     */
    public function getAWebberData($token)
    {
        list($app_key, $app_secret, $request_token, $token_secret, $oauth_verifier) = explode("|", $token);

        $aweberData = \AWeberAPI::getDataFromAweberID($token);

        if (empty($aweberData[3])) {
            throw new \Error("Missing vital information.");
        }

        return [
            'app_key' => $app_key,
            'app_secret' => $app_secret,
            'request_token' => $request_token,
            'token_secret' => $token_secret,
            'oauth_verifier' => $oauth_verifier,
            'access_token' => $aweberData[2],
            'access_token_secret' => $aweberData[3],
            'token' => $token
        ];
    }
}