<?php

use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::controller(UserController::class)->group(function () {
    Route::get("/users", "login");
    Route::post("/users", "create");
});

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');
