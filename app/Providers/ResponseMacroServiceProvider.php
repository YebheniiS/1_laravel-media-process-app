<?php

namespace App\Providers;


use Illuminate\Support\Facades\Response;
use Illuminate\Support\ServiceProvider;

class ResponseMacroServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        /**
         * 200 OK
         *
         * Success response should pass in an array of data to
         * be returned.
         */
        Response::macro('success', function ($data) {
            return response()->json([$data], 200);
        });


        /**
         * 400 Bad Request
         *
         * Most errors will throw this response
         * userMessage - Message that will be displayed to the user
         * message - Message for a developer to debug the issue
         */
        Response::macro('error', function ($userMessage = "", $message = "") {
            return response()->json([
                'userMessage'  => $userMessage,
                'message' => $message,
            ], 400);
        });


        /**
         * 401 Unauthorized
         *
         * This means the user isn’t not authorized to access a resource.
         * It usually returns when the user isn’t authenticated.
         */
        Response::macro('unauthorized', function () {
            return response()->json([
                'userMessage'  => 'Unauthorised',
            ], 401);
        });


        /**
         * 403 Forbidden
         *
         * This means the user is authenticated, but it’s not allowed to access a resource.
         */
        Response::macro('forbidden', function () {
            return response()->json([
                'userMessage'  => 'You don\'t have permission to access this resource',
            ], 403);
        });

        
        /**
         * Sometimes we may need to send a custom http response (this should be avoided
         * when possible as the specific codes used in the errors above are whats handled
         * in the front end)
         */
        Response::macro('custom', function ($status, $userMessage, $message = "") {
            return response()->json([
                'userMessage'  => $userMessage,
                'message' => $message,
            ], $status);
        });
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
