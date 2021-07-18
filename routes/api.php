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

Route::prefix('panel')->group(function () {
    
    Route::get('user/img/{filename}', [UserController::class, 'getUserImg']);
});

Route::middleware('auth:api')->prefix('panel')->group(function () {
    Route::get('user/identified', [UserController::class, 'user_identified']);
    
    Route::get('user/permissions', [UserController::class, 'user_permissions']);

    Route::get('user/role', [UserController::class, 'user_roles_permissions']);
    Route::post('user', [UserController::class, 'index']);
    Route::resource('user', UserController::class,['except'=>['create','store','index']])->names('user');
    Route::post('user/upload/{user}', [UserController::class, 'uploadImgUser']);
    
    Route::post('roles', [RoleController::class, 'index']);
    Route::resource('role', RoleController::class,['except'=>['index']])->names('role');
    
    Route::post('categories', [CategoryController::class, 'index']);
    Route::resource('category', CategoryController::class,['except'=>['index']])->names('category');
    
    Route::post('permissions', [PermissionController::class, 'index']);
    Route::resource('permission', PermissionController::class,['except'=>['index']])->names('permission');

    
    
});
