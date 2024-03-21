<?php

namespace App\Http\Middleware;

use App\Models\User;
use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\Contracts\HasApiTokens;

class AuthenticateApi extends Middleware
{
    /**
     * Проверка токена из заголовков, из формы и из authorisation
     *
     * @param  \Illuminate\Http\Request  $request
     * @return string|null
     */
    protected function authenticate($request, array $guards)
    {
        $token = $request->query('token_api');
        if(empty($token)){
            $token = $request->input('token_api');
        }
        if(empty($token)){
            $token = $request->bearerToken('token_api');
        }
        $user = User::where('email', $request->header('email'))->first();
        if($user){
            if ($token === $user->tokens->last()->token && Hash::check($request->header('password'), $user->password)) {
                return;
            }
        }
       $this->unauthenticated($request,$guards);
    }
}
