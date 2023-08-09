<?php

namespace App\Providers;

use App\Models\User;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;


class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        'App\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        Gate::define('update-model', function ($user, $model) {
            return (
                $user->id === $model->user_id ||
                $user->parent_user_id === $model->user_id
            );
        });

        ResetPassword::createUrlUsing(function (User $user, string $token) {
            return env('INTERACTR_APP_URL').'/password/reset/'.$token;
        });
    }
}
