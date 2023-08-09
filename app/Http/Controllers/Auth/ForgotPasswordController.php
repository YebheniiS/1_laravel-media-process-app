<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Repositories\AuthRepository;
use Illuminate\Http\Request;
use App\Models\User;

class ForgotPasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset emails and
    | includes a trait which assists in sending these notifications from
    | your application to your users. Feel free to explore this trait.
    |
    */

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    public function sendResetLinkEmail(Request $request) {

        $email = $request->email ?? null;

        $doesUserExist = User::where('email', $email)->first();

        if(! $doesUserExist){
            return response()->json([
                'success' => false,
                'message' => 'Email not found',
            ], 500);
        }        
        
        $repo = app()->make(AuthRepository::class);
        return $repo->sendResetLinkEmail($email);
    }
}
