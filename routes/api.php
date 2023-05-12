<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\WriterController;
use App\Http\Controllers\API\PublisherController;

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

Route::group(['prefix'=>'writer'], function(){
    Route::get('/', [WriterController::class, 'index']);
    Route::get('/{id}', [WriterController::class, 'show']);
    Route::post('/add', [WriterController::class, 'store']);
    Route::post('/edit/{id}', [WriterController::class, 'update']);
    Route::post('/delete/{id}', [WriterController::class, 'delete']);
});

Route::group(['prefix'=>'publisher'], function(){
    Route::get('/', [PublisherController::class, 'index']);
    Route::get('/{id}', [PublisherController::class, 'show']);
    Route::post('/add', [PublisherController::class, 'store']);
    Route::post('/edit/{id}', [PublisherController::class, 'update']);
    Route::post('/delete/{id}', [PublisherController::class, 'delete']); 
});