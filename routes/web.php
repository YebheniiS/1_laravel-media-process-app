<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where to place all routes that are access directly (not via an API request). This
| means these routes shouldn't have any authentication needed
*/


/* use App\Models\AccessLevel;
 use App\Models\AccessLevelUser;
*/
Route::post('/bunnycdn/webhook', 'BunnyCdnVideoController@webhook');

/** Allow a user to list all projects via a public api endpoint, validates against an API key (very basic)  */
Route::any('/projects/list', 'ProjectController@api');

/** Used to display a users privacy policy when GDPR is enabled  */
Route::get('/user/{user}/policy', 'UserController@showPrivacyPolicy');

/** Used to debug a users project */
// Route::get('preview/{project}', 'ProjectController@preview');

/** Authentication Routes */
Route::get('password/reset/{token}', 'Auth\ResetPasswordController@showResetForm')->name('password.reset'); // This is required because of laravels stupid mail client. But we don't actually use it.
Route::get('/register/newUser', function(){
    return view('register.checkEmail');
});

/** JVZoo Integration Routes */
Route::post('/register/checkEmail', 'JvZooController@checkEmail');
Route::post('/register/checkTransactionId', 'JvZooController@checkTransactionId');
Route::post('/register/createUser', 'JvZooController@createUser');


// Temp route just for testing chagnes to the encoder
Route::get('/bunny-encoder', function(){

    $repo = app()->make(\App\Repositories\BunnyCdnVideoRepository::class);

    return $repo->createVideo(\App\Media::findOrFail(135184));
});

Route::get('api/get-whitelabel-for-domain', 'WhitelabelController@getWhitelabelForDomain');

Route::get('api/get-user-logins', [\App\Http\Controllers\ReportingController::class, 'getUserLastLogins']);

Route::get('api/templates-used-report', [\App\Http\Controllers\ReportingController::class, 'templatesUsedReport']);

/** Just added so the root doesn't return an error  */
Route::get('/', function () {
  return view('welcome');
});
