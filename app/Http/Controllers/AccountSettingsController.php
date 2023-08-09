<?php

namespace App\Http\Controllers;

use App\Repositories\UserRepository;

class AccountSettingsController extends Controller
{

    public function validateIntegration($type) {
        $success = false;
        $data = request()->all();
        $userRepo = app()->make(UserRepository::class);

        try {
            switch ($type) {
                case 'integration_youzign':
                    $success = $userRepo->checkYouzignAPIKeys($data);
                    break;
                case 'integration_activecampaign':
                    $success = $userRepo->getActiveCampaigns($data);
                    break;
                case 'integration_mailchimp':
                    $success = $userRepo->getMailchimpLists($data);
                    break;
                case 'integration_getresponse':
                    $success = $userRepo->getGetResponseLists($data);
                    break;
                case 'integration_sendlane':
                    $success = $userRepo->getSendlaneLists($data);
                    break;
                case 'integration_aweber':
                    $success = $userRepo->getAWebberData($data);
                    break;

                default:
                    $success = true;
            }
        } catch (\Exception $error) {
            $success = false;
        }

        return response()->json( compact('success') );
    }
}
