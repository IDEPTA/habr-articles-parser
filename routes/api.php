<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RedisController;
use App\Http\Controllers\ArticlesController;
use App\Http\Controllers\MemcachedController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('/article-search', ArticlesController::class);
Route::controller(RedisController::class)->prefix('redis')->group(function () {
    Route::post('/create', 'saveToRedis');
    Route::post('/', 'getFromRedis');
    Route::post('/delete', 'deleteFromRedis');
    Route::get('/clear', 'clearRedis');
});


Route::controller(MemcachedController::class)
    ->prefix('memcached')->group(function () {
        Route::post('/set', 'set');
        Route::post('/get', 'get');
        Route::post('/delete', 'delete');
        Route::get('/has', 'has');
    });
