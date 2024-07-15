<?php

use App\Http\Controllers\Api\ApiController;
use \App\Http\Controllers\Api\ApiLectureController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use \App\Http\Controllers\Api\ApiClasController;
use \App\Http\Controllers\Api\ApiTasksController;


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


Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::prefix('/tasks')->group(function () {
    Route::get('/getall', [ApiTasksController::class, 'all']);
    Route::get('/getbyid/{id}', [ApiTasksController::class, 'getone'])->where('id', '\d+');
    Route::post('/create', [ApiTasksController::class, 'new']);
    Route::put('/update', [ApiTasksController::class, 'update']);
    Route::delete('/delete', [ApiTasksController::class, 'delete']);
    Route::get('/search', [ApiTasksController::class, 'search']);
});
