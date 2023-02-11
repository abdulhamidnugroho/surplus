<?php

use App\Http\Controllers\API\CategoryController;
use App\Http\Controllers\API\ImageController;
use App\Http\Controllers\API\ProductController;
use Illuminate\Http\Request;
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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::apiResources([
    'categories' => CategoryController::class,
    'products' => ProductController::class,
]);

Route::controller(ImageController::class)->group(function () {
    Route::get('/images', 'index');
    Route::post('/images', 'store');
    Route::get('/images/{id}', 'show');
    Route::post('/images/update/{id}', 'update');
    Route::delete('/images/{id}', 'destroy');
});
