<?php

namespace App\Providers;

/***********Updated by Yuri on 15-6-2023*****************/
/*use App\Models\AccessLevel;*/

use App\Nova\Metrics\NewUsers;
use App\Nova\Metrics\ActiveUsers;
use App\Nova\Metrics\ActiveUsersPerDay;
use App\Nova\Metrics\NewUsersPerDay;
use App\Nova\Metrics\UsersPerPlan;
use Illuminate\Support\Facades\Gate;
use Laravel\Nova\Cards\Help;
use Laravel\Nova\Nova;
use Laravel\Nova\NovaApplicationServiceProvider;

class
NovaServiceProvider extends NovaApplicationServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();

        Nova::style('videosuite-theme', asset('css/nova-overrides.css'));
    }

    /**
     * Register the Nova routes.
     *
     * @return void
     */
    protected function routes()
    {
        Nova::routes()
                ->withAuthenticationRoutes()
                ->withPasswordResetRoutes()
                ->register();
    }

    /**
     * Register the Nova gate.
     *
     * This gate determines who can access Nova in non-local environments.
     *
     * @return void
     */
    protected function gate()
    {
        Gate::define('viewNova', function ($user) {

            /*---------------------Updated by Yuri on 15-6-2023-------------------------------*/
            
            /*$adminId = AccessLevel::where('name', 'admin')->pluck('id')->first();

            $user->load('access');

            return $user->access->contains($adminId);*/
            
            return $user->superuser;
        });
    }

    /**
     * Get the cards that should be displayed on the default Nova dashboard.
     *
     * @return array
     */
    protected function cards()
    {
        return [
            new NewUsers(), new NewUsersPerDay(),
            new ActiveUsers(), new ActiveUsersPerDay(),
            new UsersPerPlan()
        ];
    }

    /**
     * Get the extra dashboards that should be displayed on the Nova dashboard.
     *
     * @return array
     */
    protected function dashboards()
    {
        return [];
    }

    /**
     * Get the tools that should be listed in the Nova sidebar.
     *
     * @return array
     */
    public function tools()
    {
        return [];
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
