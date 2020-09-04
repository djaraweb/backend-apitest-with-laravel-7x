<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
Use Auth;

class UserController extends ApiController
{
    public function register(Request $request){

        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required'
        ]);

        request()->merge(['password' => bcrypt(request('password'))]);
        $input = request(['name','email', 'password']);
        $user = User::create($input);

        return $this->responseToSuccess(['message' => 'Usuario Creado Correctamente']);

    }

    public function login(Request $request){

        $request->validate([
            'email'       => 'required|string|email',
            'password'    => 'required|string'
        ]);

        $credentials = request(['email', 'password']);

        if (!Auth::attempt($credentials)) {
            ////Unauthorized User
            return $this->responseToError(['message' => 'Usuario, clave son incorrectos'],401);
        }else {
            $user = auth()->user();
            $token = $user->createToken('Personal Token')->accessToken;

            return $this->responseToSuccess(['message' => 'Usuario, logueado correctamente',
                                             'token' => $token]);
        }
    }

    public function logout()
    {
    	auth()->user()->tokens->each(function($token, $key){
    		$token->delete();
    	});

        return $this->responseToSuccess(['message' => 'Successfully logged out']);
    }



}
