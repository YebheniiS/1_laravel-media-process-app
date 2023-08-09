<?php

namespace App\Rules;

use App\Repositories\UserRepository;
use Illuminate\Contracts\Validation\Rule;

class ValidateIntegration implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {

    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $data)
    {
        $response = false;
        $userRepo = app()->make(UserRepository::class);

        try {
            switch ($attribute) {
                case 'input.integration_youzign':
                    $response = $userRepo->checkYouzignAPIKeys($data);
                    break;
                case 'input.integration_activecampaign':
                    $response = $userRepo->getActiveCampaigns($data);
                    break;
                case 'input.integration_mailchimp':
                    $response = $userRepo->getMailchimpLists($data);
                    break;
                case 'input.integration_getresponse':
                    $response = $userRepo->getGetResponseLists($data);
                    break;
                case 'input.integration_sendlane':
                    $response = $userRepo->getSendlaneLists($data);
                    break;
                case 'input.integration_aweber':
                    $response = $userRepo->getAWebberData($data);
                    break;

                default:
                    $response = true;
            }
        } catch (\Exception $error) {
            $response = false;
        }

        return $response;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'The :attribute keys are incorrect. Authorization Failed';
    }
}
