<?php

use Illuminate\Support\Facades\Route;

Route::get('{path}', static function () {
    return view('index');
})->where('path', '^(?!api).*$');
