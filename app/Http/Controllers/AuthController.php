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

    public function constractor()
    {
        $this->middleware('auth:api')->except(['login', 'register']);
    }

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




    public function refresh()
    {
        return $this->respondWithToken(auth()->refresh());
    }
}
