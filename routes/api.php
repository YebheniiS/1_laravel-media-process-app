<?php

use App\Http\Controllers\Integrations\PksIntegrationController;
use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/


Route::post('register', 'Auth\Web\RegisterController@create');

Route::post('password/reset', 'Auth\ResetPasswordController@reset');
Route::post('password/email', 'Auth\ForgotPasswordController@sendResetLinkEmail')->name('password.email');

Route::post('/media/dgshdthbdzsgrdhfbvc/generate-thumbnail-complete', 'MediaController@generateThumbnailComplete');



// Route to update the player id, we make it more secure with the random chars in url
Route::get('/iafasrgc8aw47gcntaiwgan8wc7eo/updatePlayerVersion/{version}', 'PlayerVersionController@index');

// Add Contact endpoints for the player
Route::post('/activecampaign/addContact', 'ActiveCampaignController@addContact');
Route::post('/getresponse/addContact', 'GetResponseController@addContact');
Route::post('/sendlane/addContact', 'SendlaneController@addContact');
Route::post('/aweber/addContact', 'AweberController@addContact');
Route::post('/mailchimp/addContact', 'MailchimpController@addContact');

// Share View Data
Route::get('like/{hash}', 'ShareController@like');
Route::get('unlike/{hash}', 'ShareController@unlike');

// For node server project access
Route::get('projects/hash/{hash}', 'ProjectController@getFromHash');

// TODO: this is a temp route for testing the player - delete when moved to s3
Route::get('player/{id}', 'ProjectController@player');

Route::get('preview/{id}', 'ProjectController@preview');

// Whitelabel Data
//Route::post('whitelabel', 'AgencyController@whitelabel');

// Externally accessible domain check route , look for `verify` route 👇 , for in app one
Route::post('domains/check', 'CustomDomainController@checkStatus');

// Old routes, these now DEPRICATED but leave here in case any old integrations still use
Route::post('integration/pks/ksufghksrghksafgewghyuds', [PksIntegrationController::class, 'index']);
//Route::post('integration/jvzoo/iwe7fgvwbivqrctqxf', 'IntegrationController@jvzoo');


Route::get('/privacy-policy/{user}', 'UserController@getPrivacyPolicy');




/*
   * Authentication Required
   */
Route::group(['middleware' => ['auth:sanctum']], function () {
    /** Clean Routes */
    Route::get('/loginAsUser/{id}', 'UserController@loginAsUser');

    /** Update User to a new release */
    Route::get('update/20', 'UpdateController@applyUpdate20');

    /** Upload an item to be streamed (this will be encoded) */
    Route::post('upload/stream', 'FileController@stream');
    /** Upload an item to be left in raw storage */
    Route::post('upload/image', 'FileController@uploadImage');
    /** Upload a base64 image to be encoded */
    Route::post('upload/base64', 'FileController@uploadBase64');

    Route::post('bunnycdn/poll/{bunny_cdn_video}', 'BunnyCdnVideoController@poll');
    Route::post('bunnycdn/encode/{media}', 'BunnyCdnVideoController@encodeVideo');
    Route::post('bunnycdn/get', 'BunnyCdnVideoController@getVideo');

    /** Old Routes  */
    Route::post('analytics/{projectId}/pixel/{price}', 'AnalyticsController@generatePixel');

    Route::get('languages', 'TemplateLanguagesController@index');
    Route::post('languages', 'TemplateLanguagesController@store');
    Route::delete('languages/{language}', 'TemplateLanguagesController@destroy');

    Route::post('s3/signature-handler', 'PreSignedRequestController@post');

    Route::put('interactions', 'InteractionController@save');
    Route::post('interactions/{interaction}/elementGroup', 'InteractionController@addToElementGroup');

    Route::post('elements/applyTemplate', 'ElementController@applyTemplate');

    Route::post('modals/{id}/elements', 'ModalController@createElement');
    Route::post('modals/applyTemplate/', 'ModalController@applyTemplate');


    // @todo clean up the controllers below when done
    //Route::post('image/upload', 'ImageController@upload');
    //Route::post('file/upload', 'FileController@store');

    // TODO: IMPORTANT: these will need to be authenticated by a separate api key as they will be
    // hit by the player itself when an end user submits their email

    // Active Campaign
    Route::get('/activecampaign/getLists', 'ActiveCampaignController@getLists');
    // Get Response
    Route::get('/getresponse/getLists', 'GetResponseController@getCampaigns');
    // Mailchimp
    Route::get('/mailchimp/getLists', 'MailchimpController@getLists');
    // Send Lane
    Route::get('/sendlane/getLists', 'SendlaneController@getLists');
    // aWeber
    Route::get('/aweber/getLists', 'AweberController@getLists');

    // agency domain route , route for checking from external apis is ☝, outsite of middleware
    Route::post('domains/verify', 'CustomDomainController@addDomain');
    Route::post('domains/remove', 'CustomDomainController@removeDomain');

    Route::post('share-page/screenshot', 'ShareController@screenshot');

    Route::post('account/integration/{type}/validate', 'AccountSettingsController@validateIntegration');

    Route::get('projects', 'ProjectController@projects');

    Route::get('projects/getPlayerWrapperUrl', 'ProjectController@getPlayerWrapperUrl');
});

// temp url for test
Route::get('/get-whitelabel-for-domain', 'WhitelabelController@getWhitelabelForDomain');

// test api
Route::get('/getUser', 'TestController@getUser');
Route::get('/getUserPlan', 'TestController@getUserPlan');
Route::get('/getStorageUsed', 'TestController@analyticsTest');
Route::get('/isStorageLeft', 'TestController@isStorageLeft');