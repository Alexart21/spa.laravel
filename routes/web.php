<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ContentController;
use App\Http\Controllers\OAuth\OauthController;
use App\Http\Controllers\ApiController;
use App\Http\Controllers\TestController;
use App\Http\Controllers\PostsController;
use App\Http\Controllers\CallsController;
use App\Http\Controllers\ChatController;

Route::post('/test', [ TestController::class, 'test' ]);
Route::post('/upload', [ TestController::class, 'upload' ]);
Route::get('/images', [ TestController::class, 'images' ]);
Route::post('/remove', [ TestController::class, 'remove' ]);

Route::get('/chat/all', [ ChatController::class, 'all' ]);
Route::get('/chat/update', [ ChatController::class, 'update' ]);
Route::post('/chat/store', [ ChatController::class, 'store' ]);
// можешь все закрыть аутентификацией
//Route::middleware('auth')->group(function () {
    Route::post('/crop', [ TestController::class, 'crop' ]);
//});

Route::post('/mail', [ PostsController::class, 'store' ])->name('mail.store');
Route::post('/zvonok', [ CallsController::class, 'store' ])->name('zvonok.store');

Route::get('/', [ ContentController::class, 'index' ])->name('content.index');
Route::get('/{page}', [ ContentController::class, 'index' ])->whereIn('page', ['sozdanie', 'prodvijenie', 'portfolio', 'parsing', 'location', 'scroll', 'address', 'test', 'dragdrop', 'photo', 'cropper', 'chat']);

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
