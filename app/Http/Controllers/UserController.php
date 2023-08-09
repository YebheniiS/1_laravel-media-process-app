<?php

namespace App\Http\Controllers;

use App\Repositories\UserRepository;
use App\Models\User;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    /**
     * Login as another user
     *
     * @param $userId
     * @return JsonResponse
     * @throws BindingResolutionException
     */
    public function loginAsUser($userId)
    {
        $repo = app()->make(UserRepository::class);
        $token = $repo->loginAsUser($userId);

        return response()->json(['token' => $token], 200);
    }


}
