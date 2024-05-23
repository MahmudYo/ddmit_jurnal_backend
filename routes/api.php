<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\EditingPersonCategory;
use App\Http\Controllers\EditingPersonCategoryController;
use App\Http\Controllers\EditingPersonController;
use App\Http\Controllers\JurnalController;
use Illuminate\Http\Request;
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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');
Route::controller(JurnalController::class)->prefix('jurnals')->group(function () {
    Route::get('/',  'index');
    Route::post('/', 'store');
    Route::get('/{id}',  'show');
    Route::post('/{id}', 'update');
    Route::delete('/{id}', 'destroy');
    Route::get('/document/{id}', 'document');
    Route::get('/document/download/{id}', 'documentDownload');
    Route::get('/image/{id}', 'getImage');
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
