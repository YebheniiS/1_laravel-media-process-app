<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Support\Facades\Auth;


class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array
     */
    protected $dontFlash = [
        'password',
        'password_confirmation',
    ];

    /**
     * Report or log an exception.
     *
     * This is a great spot to send exceptions to Sentry, Bugsnag, etc.
     *
     * @param \Throwable $exception
     * @throws Exception
     */
    public function report(\Throwable $exception)
    {
        if (env('APP_ENV') === 'local'){
            //dd($exception);
            parent::report($exception);
        }else {
            if(Auth::check()){

                /**
                 * Bound Honeybadger in app.
                 */
                if (app()->bound('honeybadger') && $this->shouldReport($exception)) {
                    app('honeybadger')->notify($exception, app('request'));
                }

//                \Log::error($exception, [
//                    'person' => ['id' => Auth::user()->id, 'name' => Auth::user()->name, 'email' => Auth::user()->email]
//                ]); //rollbar

            }else {
                \Log::error($exception); //rollbar
            }
        }
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Exception  $exception
     * @return \Illuminate\Http\Response
     */
    public function render($request, \Throwable $exception)
    {
        // This allows critical errors to be reported to the FE
        // header('Access-Control-Allow-Origin: *');
        // header('Access-Control-Allow-Methods: *');
        // header('Access-Control-Allow-Headers: *');
dd($exception);
//        return response()->error(
//            'Error',
//            $exception->getMessage()
//        );
    }
}
