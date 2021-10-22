<?php

namespace modules\Users\Repositories;

use modules\BaseRepository;
use modules\Users\Models\User;
use App\Http\Traits\ApiDesignTrait;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Validator;
use modules\Users\Interfaces\UserInterface;
use modules\Users\Rules\MatchOldPassword;

class UserRepository extends BaseRepository implements UserInterface
{
    use ApiDesignTrait;


    public function register($request)
    {
        // TODO: Implement register() method.
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

        return $this->ApiResponse(200, 'Registered Successfully', null, $user);
    }

    public function login($request)
    {
        // TODO: Implement login() method.
        $credentials = request(['email', 'password']);
        if(! $token = auth('api')->attempt($credentials)){
            return $this->ApiResponse(422, 'unauthorized');
        }
        return $this->respondWithToken($token);
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
        // TODO: Implement logout() method.
        auth()->logout();
        return $this->ApiResponse(200, 'Logged out');
    }

    public function refresh()
    {
        // TODO: Implement refresh() method.
        return $this->respondWithToken(auth()->refresh());
    }

    public function updatePassword($request)
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
        // TODO: Implement allUsers() method.
        $users = User::all();
        return $this->ApiResponse(200, 'All Users', null, $users);
    }

    public function userDetails($request)
    {
        // TODO: Implement userDetails() method.
        $user = auth()->user();
        $user = $user::first();
//        dd($user);
        if($user) return $this->ApiResponse(200, 'User details', null, $user);
        return $this->ApiResponse(200, 'Please Login First');
    }

    public function userDetailsById($request)
    {
        // TODO: Implement userDetailsById() method.
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|exists:users,id',
        ]);
        if ($validator->fails()) {
            return $this->ApiResponse(400, 'Validation Errors', $validator->errors());
        }
        $user = User::findOrFail($request->user_id);
        return $this->ApiResponse(200, 'Requested User Details', $user);
    }

    public function UserUpdateById($request)
    {
        // TODO: Implement UserUpdateById() method.
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

    public function updateUser($request)
    {
        // TODO: Implement updateUser() method.
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
            return $this->apiResponse(200, 'User updated successfully', $user);
        }

        return $this->ApiResponse(200, 'Please Login First');
    }

    public function softDeleteUser($request)
    {
        // TODO: Implement softDeleteUser() method.
        $user = User::find($request->user_id);
//        dd($user);
        if (is_null($user)) {
            return $this->ApiResponse(400, 'No User Found');
        }
        $user->delete();
        return $this->apiResponse(200,'User deleted successfully');
    }

    public function restoreUser($request)
    {
        // TODO: Implement restoreUser() method.
        $user = User::withTrashed()->find($request->user_id);
        if (!is_null($user->deleted_at)) {
            $user->restore();
            return $this->ApiResponse(200,'User restored successfully');
        }
        return $this->ApiResponse(200,'User already restored');
    }
}
