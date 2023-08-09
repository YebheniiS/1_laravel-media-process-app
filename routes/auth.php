<?php


// Interactr
use App\Http\Controllers\Auth\Api\InteractrAuthenticationController;
use App\Http\Controllers\Auth\Api\VideobubbleAuthenticationController;

Route::group(['prefix' => '/interactr'], function(){
    Route::post('authenticate', [InteractrAuthenticationController::class, 'authenticate']);
    Route::post('logout', [InteractrAuthenticationController::class, 'logout'])->middleware(['auth:sanctum']);
});


// VideoBubble
Route::group(['prefix' => '/videobubble'], function(){
    Route::post('authenticate', [VideobubbleAuthenticationController::class, 'authenticate']);
    Route::post('logout', [VideobubbleAuthenticationController::class, 'logout'])->middleware(['auth:sanctum']);
});