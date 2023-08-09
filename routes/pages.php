<?php

Route::fallback([\App\Http\Controllers\RouterController::class, 'router']);