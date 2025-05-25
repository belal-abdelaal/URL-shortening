<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::controller()->group(function () {});

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');
