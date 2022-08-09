<?php

use App\Http\Controllers\APIv1\CloudController;
use App\Http\Controllers\APIv1\AuthController;
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

Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('user', [AuthController::class, 'user']);
    Route::post('logout', [AuthController::class, 'logout']);
    Route::prefix('cloud')->group(function () {
        Route::get('/', [CloudController::class, 'index'])->name('home.index');
        Route::get('/me', [CloudController::class, 'indexMe'])->name('me.index');
        Route::get('/childrens/{id}', [CloudController::class, 'childrens'])->name('childrens.index');
        Route::post('/save/folder', [CloudController::class, 'storeFolder'])->name('folder.store');
        Route::post('/save/file', [CloudController::class, 'storeFile'])->name('file.store');
    });
});
