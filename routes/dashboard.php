<?php
declare(strict_types=1);

use App\Http\Controllers\Backend\AuthorizeController;
use App\Http\Controllers\Backend\TaxonomyController;
use App\Http\Controllers\Backend\CKFinderController;
use App\Http\Controllers\Backend\PostController;
use App\Http\Controllers\Backend\DashboardController;
use App\Http\Controllers\Backend\OpenController;
use App\Http\Controllers\Backend\SettingController;
use App\Http\Controllers\Backend\TagController;
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
Route::post('/authorize/logout', [AuthorizeController::class, 'logout']);
Route::get('/open/configuration', [OpenController::class, 'configuration']);

Route::middleware('auth:backend')->group(function() {
    Route::any('/ckfinder/connector', [CKFinderController::class, 'request']);
    Route::get('/dashboard', [DashboardController::class, 'main']);
    Route::get('/open/menu', [OpenController::class, 'menu']);
    Route::get('/user/members', [UserController::class, 'members']);
    Route::get('/settings', [SettingController::class, 'options']);
    Route::post('/setting/store', [SettingController::class, 'store']);
    Route::post('/setting/update/{id}', [SettingController::class, 'update']);
    Route::get('/posts', [PostController::class, 'posts']);
    Route::get('/post/{id}', [PostController::class, 'show']);
    Route::post('/post/store', [PostController::class, 'store']);
    Route::post('/post/update/{id}', [PostController::class, 'update']);


    Route::get('/tags', [TaxonomyController::class, 'tags']);
    Route::post('/tag/store', [TaxonomyController::class, 'storeTag']);
    Route::put('/tag/update/{id}', [TaxonomyController::class, 'updateTag']);
    Route::delete('/tag/delete/{id}', [TaxonomyController::class, 'deleteTag']);

    Route::get('/categories', [TaxonomyController::class, 'categories']);
    Route::post('/category/store', [TaxonomyController::class, 'storeCategory']);
    Route::put('/category/update/{id}', [TaxonomyController::class, 'updateCategory']);
    Route::delete('/category/delete/{id}', [TaxonomyController::class, 'deleteCategory']);

});



