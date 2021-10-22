<?php

namespace modules;

use App\Http\Interfaces\AuthInterface;
use App\Http\Traits\ApiDesignTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;

class BaseRepository
{
    use ApiDesignTrait;

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
}
