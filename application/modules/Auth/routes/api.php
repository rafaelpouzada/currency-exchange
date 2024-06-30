<?php

use Auth\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;

Route::group(['namespace' => 'Authentication'], static function () {
    Route::post('auth/register', [AuthController::class, 'register']);
    Route::post('auth/login', [AuthController::class, 'authenticate']);
});
