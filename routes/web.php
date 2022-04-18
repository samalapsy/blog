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
        'index' => 'sort'
    ]);
    Route::resource('import-posts', ImportPostController::class)->only(['create', 'store']);
    Route::get('/import-posts',[ImportPostController::class, 'create'])->name('import-posts.create');
    Route::post('/import-posts',[ImportPostController::class, 'store'])->name('import-posts.store');

});

require __DIR__.'/auth.php';

Route::get('/{post}', [HomePageController::class, 'showPost'])->name('post-details');