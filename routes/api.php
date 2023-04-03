<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ContentController;


Route::group([
    'prefix' => 'auth',
], function () {
    // здесь рефреш по старому токену
    // как такового рефреш токена нет
    Route::post('/refresh', [ AuthController::class, 'refresh' ]);
        // сюда только по действующему jwt токену
        Route::group(['middleware' => 'auth:api'], function (){
            Route::post('/countrys', [ ContentController::class, 'countrys' ]);
            Route::post('/test', [ ContentController::class, 'test' ]);
        });
        // здесь добавлен RateLimit
        /*Route::group(['middleware' => ['auth:api', 'throttle:formsLimit']], function (){
            Route::post('/countrys', [ ContentController::class, 'countrys' ]);
            Route::post('/test', [ ContentController::class, 'test' ]);
        });*/
});

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
