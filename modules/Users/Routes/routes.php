<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\AuthorizeController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use modules\Users\Controllers\UserController;
use modules\Users\Models\User;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

use App\Http\Traits\ApiDesignTrait;
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


//AUTH
Route::post('/users/register',  [UserController::class, 'register']);
Route::post('/users/login',  [UserController::class, 'login']);


Route::middleware('auth:api')->group(function () {
    Route::post('/users/logout',  [UserController::class, 'logout']);
    Route::post('/users/refresh',  [UserController::class, 'refresh']);
    Route::post('/users/me',  [UserController::class, 'me']);
});

////////////////////////////////////////////////////////////////////////////////

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


//PERMISSIONS
Route::post('/create-role',  function (){
    $role = Role::create(['name' => 'writer']);
    return $role;
});
Route::post('/create-per',  function (){
    $permision = Permission::create(['name' => 'edit-articles']);
    return $permision;
});

Route::get('/perm-role',  function (){
    $role = Role::where("name", "reader")->first();
    $permision = Permission::where("name", "edit-articles")->first();
//    dd($role);
    $permision->assignRole($role);
//    dd($permision);
    return $role->permissions;
});


Route::get('/assign-role',  function (){
    $role = Role::where("name", "reader")->first();
//    dd($role);
    $user = User::find(1);
//        dd($user);
    $user->assignRole($role);
    return response()->json($user->getALlPermissions(), 200);
});

Route::get('/test-per',  function (){
    $user = User::find(1);
//        dd($user);
    if($user->can('edit-articles')){
        return "yes, you can do that";
    }
    return "no, you can not do that";
});