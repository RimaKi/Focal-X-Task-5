<?php

use App\Http\Controllers\TaskController;
use App\Http\Controllers\UserController;
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

Route::post('/login', [UserController::class, 'login']);

Route::middleware('auth:api')->group(function () {
    Route::post('/logout', [UserController::class, 'logout']);
    Route::post('/change-password', [UserController::class, 'changePassword']);
    Route::apiResource('/users', UserController::class)->middleware('role:admin');


    Route::middleware('role:manager')->group(function () {
        Route::get('/deleted-tasks', [TaskController::class, 'deletedTasks']);
        Route::apiResource('/tasks', TaskController::class);
        Route::post('/assigned/{task}/to/{user}', [TaskController::class, 'assignedTo']);
    });


    Route::middleware('role:user')->group(function () {
        Route::get('/my-tasks', [TaskController::class, 'viewMyTask']);
        Route::put('/start-task/{task}', [TaskController::class, 'startTask']);
        Route::put('/finish-task/{task}', [TaskController::class, 'finishTask']);
        Route::put('/failed-task/{task}', [TaskController::class, 'failedTask']);

    });


});

