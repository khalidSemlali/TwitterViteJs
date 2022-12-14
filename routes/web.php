<?php

use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TweetController;
use Inertia\Inertia;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]); 
});

Route::get('tweets', [App\Http\Controllers\TweetController::class, 'index'])->name('tweets.index');

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', function () {
        return Inertia::render('Dashboard');
    })->name('dashboard');  
    Route::post('store', [App\Http\Controllers\TweetController::class, 'store'])->name('tweets.store');
    Route::get('followings', [App\Http\Controllers\TweetController::class, 'followings'])->name('tweets.followings');
    Route::Post('/follows/{user:id}', [App\Http\Controllers\TweetController::class, 'follows'])->name('tweets.follows');
    Route::Post('/unfollows/{user:id}', [App\Http\Controllers\TweetController::class, 'unfollows'])->name('tweets.unfollows');
    Route::get('/profile/{user:name}', [App\Http\Controllers\TweetController::class, 'profile'])->name('tweets.profile');


});
