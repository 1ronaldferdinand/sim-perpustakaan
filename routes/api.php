<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\BookController;
use App\Http\Controllers\API\BorrowController;
use App\Http\Controllers\API\WriterController;
use App\Http\Controllers\API\MemberController;
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

Route::group(['prefix'=>'member'], function(){
    Route::get('/', [MemberController::class, 'index']);
    Route::get('/{id}', [MemberController::class, 'show']);
    Route::post('/add', [MemberController::class, 'store']);
    Route::post('/edit/{id}', [MemberController::class, 'update']);
    Route::post('/delete/{id}', [MemberController::class, 'delete']); 
});

Route::group(['prefix'=>'book'], function(){
    Route::get('/', [BookController::class, 'index']);
    Route::get('/{id}', [BookController::class, 'show']);
    Route::post('/add', [BookController::class, 'store']);
    Route::post('/edit/{id}', [BookController::class, 'update']);
    Route::post('/delete/{id}', [BookController::class, 'delete']); 
});

Route::group(['prefix'=>'borrow'], function(){
    Route::get('/', [BorrowController::class, 'index']);
    Route::post('/add', [BorrowController::class, 'borrow']);
});