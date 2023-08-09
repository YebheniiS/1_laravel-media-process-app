<?php

namespace App\Http\Controllers\Auth\Api;

/*use App\Models\AccessLevel;*/
use App\Models\User;
use App\Traits\ApiResponder;
use Exception;

class InteractrAuthenticationController  extends BaseAuthenticationController  implements AuthenticationInterface
{
    use ApiResponder;

    public function authenticate()
    {
        try {
            $token =  $this->handleAuthentication("interactr");
        }catch(Exception $e){
            return $this->error($e->getMessage(), 401);
        };
        
        $user = auth()->user();

        $canAccess = $this->canUserAccessInteractr($user);
        if(! $canAccess) {
            return $this->error("User has no access to Interactr.", 401);
        }

        // Track user logins
        $this->trackUserLogin($user, 'interactr');

        $user = $this->formatUserForResponse($user);

        return $this->success([
            'user' => $user,
            'token' => $token
,        ]);
    }

    public function logout()
    {
        return $this->handleLogout();
    }


    /**
     * Add some useful stuff to the user
     * object for the font end
     */
    private function formatUserForResponse($user)
    {
        if ($user->parent_user_id > 0){
            $parent = User::findOrFail($user->parent_user_id);
            $user->is_club = $parent->is_club;
            $user->evolution = $parent->evolution;
            $user->evolution_pro = $parent->evolution_pro;
        }

        return $user;
    }

    /**
     * Here we're just going to check if the user
     * has the user->access->interactr permission
     * as the user maybe in our database but has not
     * purchased interactr.
     *
     * @param $user
     * @return bool
     * @throws \Exception
     */
    private function canUserAccessInteractr($user)
    {
        if(!$user->usage_plan_id) return false;
        return true;
    }
}
