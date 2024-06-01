<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\EditingPersonCategoryController;
use App\Http\Controllers\EditingPersonController;
use App\Http\Controllers\JurnalController;
use App\Http\Controllers\SearchController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::group([

    'middleware' => 'api',
    'namespace' => 'App\Http\Controllers',
    'prefix' => 'auth'

], function ($router) {

    Route::post('login', 'AuthController@login');
    Route::post('logout', 'AuthController@logout');
    Route::post('refresh', 'AuthController@refresh');
    Route::post('me', 'AuthController@me');
    Route::post('register', 'AuthController@register');
});

Route::get('/ok', [AuthController::class, 'ok']);
Route::get('/search/{query}', [SearchController::class, 'search']);



Route::controller(JurnalController::class)->prefix('jurnals')->group(function () {
    Route::get('/',  'index');
    Route::post('/', 'store');
    Route::get('/{id}',  'show');
    Route::post('/{id}', 'update');
    Route::delete('/{id}', 'destroy');
    Route::get('/document/{id}', 'document');
    Route::get('/document/download/{id}', 'documentDownload');
    Route::get('/image/{id}', 'getImage');
    Route::get('/year/{year}', 'filterYear');
});
Route::controller(EditingPersonCategoryController::class)->prefix('editing_categories')->group(function () {
    Route::get('/',  'index');
    Route::post('/', 'store');
    Route::get('/{id}',  'show');
    Route::put('/{id}', 'update');
    Route::delete('/{id}', 'destroy');
    Route::get('/persone/{id}', 'persons');
});
Route::controller(EditingPersonController::class)->prefix('editing_persons')->group(function () {
    Route::get('/',  'index');
    Route::post('/', 'store');
    Route::get('/{id}',  'show');
    Route::post('/{id}', 'update');
    Route::delete('/{id}', 'destroy');
    Route::get('/category/{id}', 'categoryPersonShow');
    Route::get('/image/{id}', 'getImage');
});
