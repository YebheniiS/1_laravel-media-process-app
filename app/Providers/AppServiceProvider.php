<?php

namespace App\Providers;

use App\ButtonElement;
use App\FormElement;
use App\ImageElement;
use App\Observers\ButtonElementObserver;
use App\Observers\FormElementObserver;
use App\Observers\ImageElementObserver;
use App\Observers\TextElementObserver;
use App\Observers\TriggerElementObserver;
// use App\Observers\UserObserver;
use App\TextElement;
use App\TriggerElement;
use App\Models\User;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {

        Paginator::useBootstrap();
        //
        ButtonElement::observe(ButtonElementObserver::class);
        TextElement::observe(TextElementObserver::class);
        FormElement::observe(FormElementObserver::class);
        TriggerElement::observe(TriggerElementObserver::class);
        // User::observe(UserObserver::class);
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
//        $this->app->register(\Jenssegers\Rollbar\RollbarServiceProvider::class);
        $this->app->register(ResponseMacroServiceProvider::class);
    }
}
