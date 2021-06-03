<?php

use App\Http\Controllers\Role_User\CategoryController;
use App\Http\Controllers\Role_User\PermissionController;
use App\Http\Controllers\Role_User\RoleController;
use App\Http\Controllers\Role_User\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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
Route::prefix('auth')->group(function () {
    Route::post('register', [UserController::class, 'register']);
    Route::post('login', [UserController::class, 'login']);
});


Route::middleware('auth:api')->prefix('panel')->group(function () {
    Route::resource('user', UserController::class,['except'=>['create','store']])->names('user');
    Route::resource('role', RoleController::class)->names('role');
    Route::resource('category', CategoryController::class)->names('category');
    Route::resource('permission', PermissionController::class)->names('permission');

    
    
});
