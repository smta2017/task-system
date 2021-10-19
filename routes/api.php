<?php

use App\Http\Controllers\AuthController;
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

Route::post('/users/register',  [AuthController::class, 'register']);
Route::post('/users/login',  [AuthController::class, 'login']);
Route::post('/users/logout',  [AuthController::class, 'logout']);
Route::post('/users/refresh',  [AuthController::class, 'refresh']);
Route::post('/users/me',  [AuthController::class, 'me']);
Route::post('/users/update-password',  [AuthController::class, 'updatePassword']);
Route::get('/users/all-users',  [AuthController::class, 'allUsers']);
Route::get('/users/show',  [AuthController::class, 'userDetails']);
Route::get('/users/show-by-id',  [AuthController::class, 'userDetailsById']);
Route::post('/users/edit-by-id',  [AuthController::class, 'UserUpdateById']);
Route::post('/users/edit',  [AuthController::class, 'updateUser']);
Route::post('/users/delete',  [AuthController::class, 'softDeleteUser']);
Route::post('/users/restore',  [AuthController::class, 'restoreUser']);
Route::post('/users/refresh',  [AuthController::class, 'refresh']);
