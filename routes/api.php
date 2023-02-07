<?php

use App\Models\Role;
use App\Models\User;
use App\Models\Tasks;
use App\Models\Status;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\TasksController;
use App\Http\Controllers\StatusController;

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

    Route::controller(UserController::class)->group(function () {
        Route::post('register', 'register');
        Route::post('login', 'authenticate');
    });

    Route::controller(TasksController::class)->middleware(['jwt.verify','admin'])->group(function () {
        Route::get('tasks', 'index');
        Route::post('task', 'store');
        Route::get('task/{id}', 'show');
        Route::post('task/{id}', 'update');
        Route::delete('task/{id}', 'destroy');
        Route::put('assign/task/{id}', 'assign_task');
        Route::delete('deassign/task/{id}', 'deassign_task');
        Route::get('search/{keyword}', 'search');
    });
    
    Route::controller(RoleController::class)->middleware(['jwt.verify','admin'])->group(function () {
        Route::get('roles', 'index');
        Route::post('role', 'store');
        Route::post('role/{id}', 'update');
        Route::delete('role/{id}', 'destroy');
    });
    
    Route::controller(StatusController::class)->middleware(['jwt.verify','admin'])->group(function () {
        Route::get('statuses', 'index');
        Route::post('status', 'store');
        Route::post('status/{id}', 'update');
        Route::delete('status/{id}', 'destroy');
    });
    
    Route::controller(TasksController::class)->middleware(['jwt.verify'])->group(function () {
        Route::get('tasks', 'index');
    });
