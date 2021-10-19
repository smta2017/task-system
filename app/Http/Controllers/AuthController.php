<?php

namespace App\Http\Controllers;

use App\Http\Traits\ApiDesignTrait;
use App\Models\User;
use App\Rules\MatchOldPassword;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    //
    use ApiDesignTrait;

    public function register(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'name'                  => 'required',
            'email'                 => 'required|email|unique:users,email',
            'password'              => 'required|min:6',
            'organization_id' => 'required|exists:organizations,id',
        ]);
        if($validator->fails()) {
            return $this->ApiResponse(400, 'Validation Errors', $validator->errors());
        }

        try {
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'organization_id' => $request->organization_id,
            ]);
        }catch (\Exception $exception){
            return $this->ApiResponse(200, 'Register Problem', );
        }


//        $vendor->assignRole('vendor');
        return $this->ApiResponse(200, 'Registered Successfully', null, $user);
    }




    public function login()
    {
        // TODO: Implement login() method.

        $credentials = request(['email', 'password']);
        if(! $token = auth('api')->attempt($credentials)){
            return $this->ApiResponse(422, 'unauthorized');
        }
        return $this->respondWithToken($token);
    }


    public function auth($guard, $data)
    {
        if (auth()->guard($guard)->attempt($data)) {
            $user = auth()->guard($guard)->user();
            if ($user->deleted_at != Null) {
                return "validation error";
            } else {
                $token = $user->createToken('token-name')->plainTextToken;
                return $this->ApiResponse(200, 'Done', null, $token);
            }
        }
        return $this->ApiResponse(401, 'Bad credentials');
    }


    protected function respondWithToken($token)
    {
        $userData = User::find(auth('api')->user()->id);

        $data = [
            'name' =>$userData->name,
            'email' =>$userData->email,
            'access_token' => $token,
        ];

        return $this->ApiResponse(200, 'done', null, $data);

    }



    public function logout()
    {
//        // TODO: Implement logout() method.
        auth()->logout();
        return $this->ApiResponse(200, 'Logged out');
    }




    public function updatePassword(Request $request)
    {
        // TODO: Implement updatePassword() method.

        $validation = Validator::make($request->all(), [
            'old_password' => ['required', new MatchOldPassword],
            'new_password' => 'required|min:6'
        ]);
        if($validation->fails()) {
            return $this->apiResponse(400, 'validation error', $validation->errors());
        }

        $user = User::find(auth('api')->user()->id);
        $user->update([
            'password' => Hash::make($request->new_password),
        ]);
        return $this->apiResponse(200, 'Password updated successfully');
    }


    public function allUsers()
    {
        // TODO: Implement allVendors() method.
        $users = User::all();
        return $this->ApiResponse(200, 'All Users', null, $users);
    }


    public function userDetails(Request $request)
    {
        // TODO: Implement vendorDetails() method.
        $user = auth()->user();
        $user = $user::first();
//        dd($user);
        if($user) return $this->ApiResponse(200, 'User details', null, $user);
        return $this->ApiResponse(200, 'Please Login First');
    }


    public function userDetailsById(Request $request)
    {
        // TODO: Implement vendorDetails() method.
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|exists:users,id',
        ]);
        if ($validator->fails()) {
            return $this->ApiResponse(400, 'Validation Errors', $validator->errors());
        }
        $user = User::findOrFail($request->user_id);
        return $this->ApiResponse(200, 'Requested User Details', $user);
    }

    public function UserUpdateById(Request $request)
    {
        // TODO: Implement vendorDetails() method.
//        dd($request);
        $validator = Validator::make($request->all(), [
            'user_id'             => 'required|exists:users,id',
            'name'                  => 'required',
            'email'                 => 'required|email|unique:users,email,'.$request->user_id,
            'password'              => 'required|min:6',
            'organization_id'       => 'required|exists:organizations,id',
        ]);
        if ($validator->fails()) {
            return $this->ApiResponse(400, 'Validation Errors', $validator->errors());
        }
        $user = User::findOrFail($request->user_id);
        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'organization_id' => $request->organization_id,
        ]);

        return $this->ApiResponse(200, 'User Updated Successfully', $user);
    }


    public function updateUser(Request $request)
    {
        // TODO: Implement updateVendor() method.

        $user = auth()->user();
        if($user) {
            $validator = Validator::make($request->all(), [
                'name' => 'required',
                'email' => 'required|email|unique:users,email,'.$user->id,
                'password' => 'required|min:6',
                'organization_id' => 'required|exists:organizations,id',
            ]);
            if ($validator->fails()) {
                return $this->ApiResponse(400, 'Validation Errors', $validator->errors());
            }
            $user->update([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'organization_id' => $request->organization_id,
            ]);
            return $this->apiResponse(200, 'Vendor updated successfully', $user);
        }

        return $this->ApiResponse(200, 'Please Login First');
    }



    public function softDeleteUser(Request $request)
    {
        // TODO: Implement softDeleteVendor() method.
        $user = User::find($request->user_id);
        if (is_null($user)) {
            return $this->ApiResponse(400, 'No User Found');
        }
        $user->delete();
        return $this->apiResponse(200,'User deleted successfully');
    }


    public function restoreUser(Request $request)
    {
        // TODO: Implement restoreVendor() method.
        $user = User::withTrashed()->find($request->user_id);
        if (!is_null($user->deleted_at)) {
            $user->restore();
            return $this->ApiResponse(200,'User restored successfully');
        }
        return $this->ApiResponse(200,'User already restored');
    }



    public function refresh()
    {
        return $this->respondWithToken(auth()->refresh());
    }
}
