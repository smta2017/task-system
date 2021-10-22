<?php

namespace App\Http\Middleware;

use App\Http\Traits\ApiDesignTrait;
use Closure;
use Illuminate\Http\Request;

class EmailVerify
{
    use ApiDesignTrait;
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $customer = auth('sanctum')->user();
//        if(!is_null($customer->email_verified_at)){
//            return $next($request);
//        }
//        return $this->ApiResponse(200, 'Please Verify Your Email');

        if(is_null($customer->email_verified_at)) { return 'Email not verified!'; }
        return $next($request);
    }
}
