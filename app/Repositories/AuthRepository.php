<?php


namespace App\Repositories;


use App\Models\User;
use Illuminate\Auth\Passwords\PasswordBroker;
use Illuminate\Foundation\Auth\ResetsPasswords;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;
use Tymon\JWTAuth\Exceptions\UserNotDefinedException;

class AuthRepository
{
    protected $passwordBroker;

    use ResetsPasswords;

    public function __construct(PasswordBroker $passwordBroker)
    {
        $this->passwordBroker = $passwordBroker;
    }

    /**
     * Authenticate a user
     *
     * @param $credentials
     * @return array
     */
    public function login($credentials) : array
    {
        try {
            // attempt to verify the credentials and create a token for the user
            if (! $token = auth()->attempt($credentials)) {
                throw new \Error('Invalid email or password');
            }
        } catch (JWTException $e) {
            // something went wrong whilst attempting to encode the token
            throw new \Error('Server Error');
        }

        $user = auth()->user();
        $user = $this->formatForResponse($user);

        // all good so return the token
        return compact('user', 'token');
    }

    /**
     * Re-authenticate logged in user
     * @return array
     */
    public function reauthenticate() : array
    {
        try {
            $user = auth()->userOrFail();

        }catch (UserNotDefinedException $e) {

            throw new \Error('Invalid email or password');
        } catch (TokenExpiredException $e) {

            throw new \Error('Login token expired. Please login to refresh the token.');
        } catch (TokenInvalidException $e) {

            throw new \Error('Token Invalid');
        } catch (JWTException $e) {

            throw new \Error('Token Absent');
        }

        $user = $this->formatForResponse($user);
        $token = auth()->refresh();

        return compact('user', 'token');
    }

    /**
     * Logout logged in user
     * @return bool
     */
    public function logout() : bool
    {
        // Force Logout
        auth()->invalidate();
        auth()->logout();

        return true;
    }

    /**
     * Reset user forgotten password by email
     * @param $email
     * @return array
     */
    public function sendResetLinkEmail($email) : array
    {
        try {
            // We will send the password reset link to this user. Once we have attempted
            // to send the link, we will examine the response then see the message we
            // need to show to the user. Finally, we'll send out a proper response.
            $res = $this->passwordBroker->sendResetLink(compact('email'));
            
            /**
             * Get the response for a successful password reset link.
             */
            if ($res == Password::RESET_LINK_SENT) {
                $response = [
                    'success' => true,
                    'message' => 'Password reset link successfully sent'
                ];

               return $response;
            }
        } catch (\Exception $e){
            $response = [
                'success' => false,
                'message' => ''
            ];
            $response['message'] = $e->getMessage();
            return $response;
        }
    }

    public function resetPassword($data)
    {
        $request = new Request();
        $request->setMethod('POST');
        $request->request->add($data);

        /**
         * Use ResetsPassword trait
         */
        $data = $this->reset($request);
        return $data;
    }

    /**
     * Add some useful stuff to the user
     * object for the front end
     *
     * @param $user
     * @return mixed
     */
    private function formatForResponse($user) : User
    {
        if ($user->parent_user_id > 0){
            $parent = User::findOrFail($user->parent_user_id);
            $user->is_club = $parent->is_club;
            $user->evolution = $parent->evolution;
            $user->evolution_pro = $parent->evolution_pro;
        }

        return $user;
    }
}