<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\UserLogin;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class AuthenticationController extends Controller
{
    protected $JWTAuth;

    public function __construct()
    {
        
    }

    public function reauthenticate(Request $request) {
//         try {
//             $user = auth()->userOrFail();

//         }catch (UserNotDefinedException $e) {
//             return response()->error('Invalid email or password');
//         } catch (TokenExpiredException $e) {

//             return response()->error('Login token expired. Please login to refresh the token.');

//         } catch (TokenInvalidException $e) {

//             return response()->error('Token Invalid');

//         } catch (JWTException $e) {
//             return response()->error('Token Absent');
// //            return response()->json(['error' => true, 'message' => 'Token Absent'], $e->getStatusCode());

//         }

//         $token = auth()->refresh();
//         $user = $this->formatForResponse($user);
//         // the token is valid and we have found the user via the sub claim
//         return response()->json(compact('user', 'token'));
    }

    /**
     * Add some useful stuff to the user
     * object for the font end
     *
     * @param $user
     * @return mixed
     */
    private function formatForResponse($user){
        if ($user->parent_user_id > 0){
            $parent = User::findOrFail($user->parent_user_id);
            $user->is_club = $parent->is_club;
            $user->evolution = $parent->evolution;
            $user->evolution_pro = $parent->evolution_pro;
        }

        return $user;
    }

    public function authenticate(Request $request)
    {
        // grab credentials from the request
        $credentials = $request->only('email', 'password');

        // try {
        //     // attempt to verify the credentials and create a token for the user
        //     if (! $token = auth()->attempt($credentials)) {
        //         return response()->error('Invalid email or password');
        //     }
        // } catch (JWTException $e) {
        //     // something went wrong whilst attempting to encode the token
        //     return response()->error('Server Error');
        // }

        $user = auth()->user();

                
        // Track user logins
        try {
            // Local ip will fail so we jjust override it on locoal dev
            $ip = (env('APP_ENV')==='local') ? '69.162.81.155' : $request->ip();

          $response = Http::get(env('IP_API_BASE_URL'). '/json/' . $ip . '?key=jx8uAK8h3dej2uC');

          if ( $response->ok() ) {
              $jsonResponse = $response->json();

            UserLogin::create([
              'user_id' => $user->id,
              'country_code' => $jsonResponse['countryCode'],
              'region_code' => $jsonResponse['region']
            ]);



          } else {
            logger($response->json());
          }

        } catch (\Exception $e) {
          logger(json_encode($e));
        }


        $user = $this->formatForResponse($user);
        // all good so return the token
        return response()->json(compact('token', 'user'));
    }

    public function logout(Request $request) {
        // Force Logout
        auth()->invalidate();
        auth()->logout();

        return [
            'status' => 'OK',
        ];
    }
}
