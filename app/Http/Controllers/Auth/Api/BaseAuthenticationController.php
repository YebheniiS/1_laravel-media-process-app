<?php

namespace App\Http\Controllers\Auth\Api;

use App\Http\Controllers\Controller;
use App\UserLogin;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Traits\ApiResponser;
use Exception;
use Illuminate\Support\Facades\Auth;

class BaseAuthenticationController extends Controller
{
    public function handleAuthentication($name): string
    {
        // grab credentials from the request
        $credentials = request()->only('email', 'password');

        if (!Auth::attempt($credentials)) {
            abort(401, "Invalid Credentials");
        };

        // all good so return the token
        $token = auth()->user()->createToken($name);
        return $token->plainTextToken;
    }


    public function handleLogout($quard = null) 
    {    
        auth()->user()->tokens()->delete();

        return true;
    }

    public function trackUserLogin($user, $app)
    {
        try {
            // Local ip will fail so we jjust override it on locoal dev
            $ip = (
                env('APP_ENV')==='local' ||
                env('APP_ENV')==='testing'
            ) ? '69.162.81.155' : request()->ip();

            // $response = Http::get(env('IP_API_BASE_URL'). '/json/' . $ip . '?key=jx8uAK8h3dej2uC');

            // if ( $response->ok() ) {
            //     $jsonResponse = $response->json();

            //     UserLogin::create([
            //         'user_id' => $user->id,
            //         'country_code' => $jsonResponse['countryCode'],
            //         'region_code' => $jsonResponse['region'],
            //         'app' => $app
            //     ]);


            // } else {
            //     throw new \Exception($response->json());
            //     logger($response->json());
            // }

        } catch (\Exception $e) {
            logger(json_encode($e));
        }
    }
}
