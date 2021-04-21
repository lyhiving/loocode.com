<?php

declare(strict_types=1);

use App\Http\Controllers\Frontend\PostController;
use App\Http\Controllers\Frontend\ToolController;
use Illuminate\Support\Facades\Route;

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

Route::post('/post/view/{id}', [PostController::class, "view"])->where('id', '[0-9]+');

Route::post('/tool/convert', [ToolController::class, "convert"]);
