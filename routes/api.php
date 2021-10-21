<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AuthorizeController;

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

Route::group(['namespace'=>'Api'],function(){

Route::group(['middleware' => 'api','prefix' => 'auth','namespace'=>'Auth'], function () {
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::post('/refresh', [AuthController::class, 'refresh']);
    Route::get('/user-profile', [AuthController::class, 'userProfile']);    
});

Route::group(['middleware'=>'jwt.verify','prefix'=>'auth','namespace'=>'Authorize'],function(){
    Route::get('/getRole',[AuthorizeController::class,'roles']);
    Route::get('/getPermission',[AuthorizeController::class,'permissions']);
    Route::post('/createPermissions',[AuthorizeController::class,'createPermissions']);
    Route::post('/createRoles',[AuthorizeController::class,'createRoles']);
    Route::post('/assignRoleToPermission',[AuthorizeController::class,'assignRoleToPermission']);
    Route::get('/rolePermission/{role_id}',[AuthorizeController::class,'rolePermission']);
    Route::post('/assignRoleToUser',[AuthorizeController::class,'assignRole']);
    Route::get('/userPermission/{user_id}',[AuthorizeController::class,'userPermission']);
    Route::get('/userRole/{user_id}',[AuthorizeController::class,'userRole']);
    Route::get('/revoke/{role_id}/{per_id}',[AuthorizeController::class,'revoke']);
});

}); 