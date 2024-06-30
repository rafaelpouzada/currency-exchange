<?php

use App\Http\Controllers\HealthcheckController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/healthcheck', [HealthcheckController::class, 'healthcheck']);

Route::get('/user', static function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');
