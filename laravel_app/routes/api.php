<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\TodoController;

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

Route::get('/allLists', [TodoController::class, 'index']);
Route::post('/createList', [TodoController::class, 'store']);
Route::post('/updateList/{id}', [TodoController::class, 'update']);
Route::post('/updateStatus/{id}', [TodoController::class, 'updateStatus']);
Route::post('/checkAll', [TodoController::class, 'checkAll']);
Route::post('/uncheckAll', [TodoController::class, 'uncheckAll']);
Route::delete('/deleteList/{id}', [TodoController::class, 'destroy']);
Route::delete('/clearCompleted', [TodoController::class, 'clearCompleted']);
