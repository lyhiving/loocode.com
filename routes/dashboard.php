<?php
declare(strict_types=1);

use App\Http\Controllers\Backend\AuthorizeController;
use App\Http\Controllers\Backend\PostsController;
use App\Http\Controllers\Backend\DashboardController;
use App\Http\Controllers\Backend\OpenController;
use App\Http\Controllers\Backend\SettingController;
use App\Http\Controllers\Backend\UserController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Dashboard Routes
|--------------------------------------------------------------------------
|
|
*/

Route::post('/authorize/login', [AuthorizeController::class, 'authenticate']);

Route::middleware('auth:backend')->group(function() {
    Route::get('/dashboard', [DashboardController::class, 'main']);
    Route::get('/open/configuration', [OpenController::class, 'configuration']);
    Route::get('/open/menu', [OpenController::class, 'menu']);

    Route::get('/user/members', [UserController::class, 'members']);

    Route::get('/setting/configuration', [SettingController::class, 'options']);

    Route::get('/content/posts', [PostsController::class, 'posts']);

    Route::post('/content/posts/store', [PostsController::class, 'store']);
    Route::post('/content/posts/update', [PostsController::class, 'update']);
});


