<?php

namespace App\Http\Controllers\Auth\Api;

use App\Models\AccessLevel;
use Exception;
use App\Traits\ApiResponder;

class VideobubbleAuthenticationController  extends BaseAuthenticationController  implements AuthenticationInterface
{
    use ApiResponder;
    public function authenticate()
    {
        try {
            $token =  $this->handleAuthentication("interactr");
        } catch (Exception $e) {
            if ($e->getMessage() === "Invalid Credentials") {
                return $this->error($e->getMessage(), 401);
            } else {
                return $this->error($e->getMessage(), 500);
            }
        };

        $user = auth()->user();

        $this->canUserAccessVideoBubble($user);

        // Track user logins
        $this->trackUserLogin($user, 'videobubble');


        return $this->success([
            'user' => $user,
            'token' => $token,
        ]);
    }

    public function logout()
    {
        return $this->handleLogout();
    }

    private function canUserAccessVideoBubble($user)
    {
        $id = AccessLevel::where('name', 'videobubble')->pluck('id')->first();

        $user->load('access');

        if ($user->access->contains($id)) {
            return true;
        }

        throw new \Exception('User doesn\'t have access to video bubble');
    }
}
