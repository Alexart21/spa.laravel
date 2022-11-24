<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PostsController;
use App\Http\Controllers\CallsController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/mail', [ PostsController::class, 'store' ])->name('mail.store');
Route::post('/zvonok', [ CallsController::class, 'store' ])->name('zvonok.store');

//Route::post('/mail/info', [ PostsController::class, 'info' ])->name('post.info');
//Route::post('/zvonok/info', [ CallsController::class, 'info' ])->name('zvonok.info');
