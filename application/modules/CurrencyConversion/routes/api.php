<?php

use CurrencyConversion\Http\Controllers\CurrencyConversionController;
use Illuminate\Support\Facades\Route;

Route::group(['namespace' => 'CurrencyConversion', 'middleware' => ['api.restrict']], static function () {
    Route::resource('currency-conversion', CurrencyConversionController::class)
        ->only(['index', 'show', 'store']);
});
