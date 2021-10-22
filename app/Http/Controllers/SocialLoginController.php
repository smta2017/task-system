<?php

namespace App\Http\Controllers;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Exception;
use Illuminate\Support\Facades\Redis;
use modules\Customers\Models\Customer;
use modules\Users\Models\User;


class SocialLoginController extends Controller
{
    public function redirectToSocial($driver)
    {
//        dd($driver);
//        dd(Socialite::driver($driver));
        return Socialite::driver($driver)->redirect();
//        dd(Socialite::driver($driver));
    }

    public function handleSocialCallback($driver)
    {

        try {
            $user = Socialite::driver($driver)->user();

            $this->registerOrLoginUser($user);

            return redirect()->route('home');

        } catch (Exception $exception) {
            dd($exception->getMessage());
        }
    }
    protected function registerOrLoginUser($request)
    {
        $user = Customer::where('email', '=', $request->email)->first();
        if (!$user) {
            $user = new Customer();
            $user->name = $request->name;
            $user->email = $request->email;
            $user->Oauth_token = $request->id;
//            $user->avatar = $request->avatar;
            $user->password =Hash::make('123456789');
            $user->save();
            $user->assignRole('customer');
        }

        Auth::login($user);

    }

}
