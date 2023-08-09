<?php
/**
 * All our external webhooks
 */

use App\Http\Controllers\Integrations\JvzooIntegrationController;
use App\Http\Controllers\Integrations\PksIntegrationController;
use App\Http\Controllers\Integrations\ThriveCartIntegrationController;
use Illuminate\Support\Facades\Config;


/**
 * Private webhooks, these have a hash in the URL to prevent them
 * being randomly accessed by anyone
 */
/**
 * Paykickstart
 */
Route::post('/pks', [PksIntegrationController::class, 'index']);

/**
 * JVZoo
 */
Route::post('/jvzoo', [JvzooIntegrationController::class, 'index']);

/**
 * ThriveCart
 */
Route::post('/thrivecart', [ThriveCartIntegrationController::class, 'index']);

