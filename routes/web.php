<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ContentController;
use App\Http\Controllers\OAuth\OauthController;
use App\Http\Controllers\ApiController;

Route::get('/', [ ContentController::class, 'index' ])->name('content.index');
Route::get('/{page}', [ ContentController::class, 'index' ])->whereIn('page', ['sozdanie', 'prodvijenie', 'portfolio', 'parsing', 'scroll', 'address', 'test', 'dragdrop']);

Route::get('/user', [ ContentController::class, 'user' ])->name('content.user');
Route::get('/csrf', [ ContentController::class, 'csrf' ])->name('content.csrf');
Route::get('/inf', [ ApiController::class, 'inf' ]);

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::get('auth/{service}', [OauthController::class, 'redirectToService'])->whereIn('service', ['google', 'github', 'mailru', 'odnoklassniki', 'vkontakte', 'yandex']);
Route::get('auth/{service}/callback', [OauthController::class, 'handleCallback'])->whereIn('service', ['google', 'github', 'mailru', 'odnoklassniki', 'vkontakte', 'yandex']);

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
