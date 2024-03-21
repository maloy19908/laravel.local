<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function register(Request $request){
        if ($request->method() === 'POST') {
            $fields = $request->validate([
                'name' => 'required|string',
                'email' => 'required|string|unique:users,email',
                'password' => 'required|string|confirmed',
            ]);
            $user = User::create([
                'name' => $fields['name'],
                'email' => $fields['email'],
                'password' => bcrypt($fields['password']),
            ]);
            $token = $user->createToken('avitotoken')->plainTextToken;
            $response = [
                'user' => $user,
                'token' => $token
            ];
        }
        return view('register');
    }

    public function login(Request $request){
        if($request->method()==='POST'){
            $fields = $request->validate([
                'email' => 'required|string',
                'password' => 'required|string',
            ]);
            // check email
            $user = User::where('email', $fields['email'])->first();
            //dd($user->tokens->last()->token);
            if(!$user || !Hash::check($fields['password'], $user->password)) {
                return response([
                    'massage' => 'Bad creds'
                ], 401);
            }
            //$token = $user->createToken('avitotoken')->plainTextToken;
            return redirect(route('home'));
        }
        return view('login');
    }
}
