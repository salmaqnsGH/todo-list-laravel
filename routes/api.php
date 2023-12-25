<?php

use App\Http\Controllers\ActivityController;
use App\Http\Controllers\TodoController;
use App\Http\Controllers\UserController;
use App\Http\Middleware\ApiAuthMiddleware;
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

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::post('/users', [UserController::class, 'register']);
Route::post('/users/login', [UserController::class, 'login']);

Route::middleware(ApiAuthMiddleware::class)->group(function(){
    Route::get('/users/current', [UserController::class, 'get']);
    Route::patch('/users/current', [UserController::class, 'update']);
    Route::delete('/users/logout', [UserController::class, 'logout']);

    Route::post('/activities', [ActivityController::class, 'create']);
    Route::get('/activities', [ActivityController::class, 'getList']);
    Route::get('/activities/{id}', [ActivityController::class, 'get'])->where('id','[0-9]+');
    Route::put('/activities/{id}', [ActivityController::class, 'update'])->where('id','[0-9]+');
    Route::delete('/activities/{id}', [ActivityController::class, 'delete'])->where('id','[0-9]+');
    
    
    Route::post('/activities/{activityID}/todos', [TodoController::class, 'create'])->where('activityID','[0-9]+');
    Route::get('/activities/{activityID}/todos/{todoID}', [TodoController::class, 'get'])->where('activityID','[0-9]+')->where('activityID','[0-9]+');
});