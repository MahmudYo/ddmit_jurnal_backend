<?php

use App\Http\Controllers\AuthController;
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
    Route::patch('/{id}', 'update');
    Route::delete('/{id}', 'destroy');
    Route::get('/document/{id}/{document_name}', 'document');
    Route::get('/document/download/{id}/{document_name}', 'documentDownload');
    Route::get('/image/{id}/{image_name}', 'getImage');
});
