<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ContentController;

Route::group([
    'prefix' => 'auth',
//    'middleware' => 'auth',
], function () {
    Route::post('login', [ AuthController::class, 'login' ]);
    Route::post('refresh', [ AuthController::class, 'refresh' ]);
    Route::post('/countrys', [ ContentController::class, 'countrys' ]);
//    Route::post('logout', [ AuthController::class, 'logout' ]);
});

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
