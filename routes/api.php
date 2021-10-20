<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


//AUTH
Route::post('/users/register',  [AuthController::class, 'register']);
Route::post('/users/login',  [AuthController::class, 'login']);
Route::post('/users/logout',  [AuthController::class, 'logout']);
Route::post('/users/refresh',  [AuthController::class, 'refresh']);
Route::post('/users/me',  [AuthController::class, 'me']);


//USER CRUD
Route::middleware('auth:api')->group(function () {
Route::post('/users/update-password',  [UserController::class, 'updatePassword']);
Route::get('/users/all-users',  [UserController::class, 'allUsers']);
Route::get('/users/show',  [UserController::class, 'userDetails']);
Route::get('/users/show-by-id',  [UserController::class, 'userDetailsById']);
Route::post('/users/edit-by-id',  [UserController::class, 'UserUpdateById']);
Route::post('/users/edit',  [UserController::class, 'updateUser']);
Route::post('/users/delete',  [UserController::class, 'softDeleteUser']);
Route::post('/users/restore',  [UserController::class, 'restoreUser']);
});
