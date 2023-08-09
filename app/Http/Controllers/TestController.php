<?php

namespace App\Http\Controllers;

use App\Repositories\UserRepository;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use App\Services\AnalyticsApi;

class TestController extends Controller
{
    public function getUser()
    {
        $user = User::where('id', 1)->first();
        return json_encode($user->addons);
    }

    public function analyticsTest() {
        $analyticsApi = new AnalyticsApi();
        $storage_used = $analyticsApi->recordStorageUsed(1, 123, 123, 12.5);
        return json_encode($storage_used);
    }

    public function getUserPlan()
    {
        $user = User::where('id', 2)->first();
        return json_encode($user->plan);
    }

    public function isStorageLeft() {
        $analyticsApi = new AnalyticsApi();
        $isStorageLeft = $analyticsApi->isStorageLeft(1, 123);
        return json_encode($isStorageLeft);
    }
}
