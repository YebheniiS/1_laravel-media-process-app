<?php

namespace App\Providers;

use App\Models\Domain;
use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
class RouteServiceProvider extends ServiceProvider
{
    /**
     * This namespace is applied to your controller routes.
     *
     * In addition, it is set as the URL generator's root namespace.
     *
     * @var string
     */
    protected $namespace = 'App\Http\Controllers';

    /**
     * Define your route model bindings, pattern filters, etc.
     *
     * @return void
     */
    public function boot()
    {
        //

        parent::boot();
    }

    /**
     * Define the routes for the application.
     *
     * @return void
     */
    public function map()
    {
        /**
         * Map all routes that use the primary domain defined in APP_DOMAIN
         */
        $this->mapWebRoutes();

        /**
         * Map all the routes that use the api, defined in APP_API_DOMAINS as a
         * comma seperated list. For whitelabel purposes we can't use videosuite or
         * interactr for all api requests so we will need to alias a whitelabel domain
         * here too
         */
        $this->mapApiRoutes();

        /**
         * Map the external webhook routes. these are prefixed with a random string to keep the
         * url from being guessed
         */
        $this->mapWebhookRoutes();

        /**
         * Map the routes for sales pages, these check the domain name against the domains table
         */
        $this->mapPagesRoutes();
    }

    /**
     * Define the "web" routes for the application.
     *
     * These routes all receive session state, CSRF protection, etc.
     */
    protected function mapWebRoutes()
    {
        Route::domain( config('domains.app') )->group(function(){
            Route::middleware('web')
                ->namespace($this->namespace)
                ->group(base_path('routes/web.php'));
        });
    }

    /**
     * Define the "api" routes for the application.
     *
     * These routes are  stateless and use api tokens for authentication.
     */
    protected function mapApiRoutes()
    {
        Route::prefix('auth')
              ->middleware('api')
              ->namespace('App\Http\Controllers\Auth\Api')
              ->group(base_path('routes/auth.php'));

        Route::prefix('api')
            ->middleware('api')
            ->namespace($this->namespace)
            ->group(base_path('routes/api.php'));

        // Because we need to support aliases in the api domain routing we
        // need to create a list of all the supported domains for 'api' and
        // declare each one dynamically
        // $domains = array_values(config('domains.api'));

        // foreach($domains as $domain) {
        //     Route::group(['domain' => $domain], $routes);
        // }
    }

    protected function mapWebhookRoutes()
    {
        Route::domain(config('domains.webhook'))
            ->middleware('webhook')
            ->prefix(config('webhooks.prefix'))
            ->group(base_path('routes/webhooks.php'));
    }

    /**
     * Define all the custom html sales page routes, the domains here are
     * defined in a database so these routes basically act as a fallback
     * to anything not matched to the routes in the above methods. We then
     * use the Router.php middleware and HandleRedirect.php middleware to
     * manage this part of the app
     */
    protected function mapPagesRoutes()
    {
        $domains = Domain::all();

        foreach($domains as $domain){
            Route::domain($domain->domain_name)->middleware(['pages'])->group(function(){
                // Route::fallback([\App\Http\Controllers\RouterController::class, 'router']);
                Route::any('{catchall}', [\App\Http\Controllers\RouterController::class, 'router'])->where('catchall', '.*')->fallback();
            });
        };
    }
}
