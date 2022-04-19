<?php

use App\Http\Controllers\HomePageController;
use App\Http\Controllers\ImportPostController;
use App\Http\Controllers\PostController;
use Illuminate\Support\Facades\Route;

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

Route::get('/', [HomePageController::class, 'index'])->name('index');

Route::middleware('auth')->prefix('dashboard')->name('dashboard.')->group(function () {
    
    Route::get('/', [PostController::class, 'index']);
    
    Route::resource('posts', PostController::class)->only(['index', 'create', 'store', 'show'])->parameters([
        'index' => 'publication_date'
    ]);
    Route::resource('import-posts', ImportPostController::class)->only(['create', 'store']);
});

require __DIR__.'/auth.php';

Route::get('/{post}', [HomePageController::class, 'showPost'])->name('post-details')->middleware('cache.headers:public;max_age=2628000;etag');