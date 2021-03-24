<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\User;
use Carbon\Carbon;


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

        return $this->responseToSuccess(['message' => 'Usuario Creado Correctamente','user'=>$user]);

    }

    public function login(Request $request){

        $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string'
        ]);

        $credentials = request(['email', 'password']);

        if (!Auth::attempt($credentials))
            return $this->responseToError('Usuario, clave son incorrectos',401);

        $user = $request->user();
        $tokenResult = $user->createToken('Personal Access Token');

        $token = $tokenResult->token;
        // if ($request->remember_me)
        //     $token->expires_at = Carbon::now()->addWeeks(1);
        // $token->save();

        return $this->responseToSuccess(
            ['message' => 'Usuario, logueado correctamente',
             'access_token' => $tokenResult->accessToken,
             'token_type' => 'Bearer',
             'expires_at' => Carbon::parse($token->expires_at)->toDateTimeString()]);

    }

    public function logout()
    {
        // $request->user()->token()->revoke();
        // return response()->json([
        //     'message' => 'Successfully logged out'
        // ]);
        
    	auth()->user()->tokens->each(function($token, $key){
    		$token->delete();
    	});

        return $this->responseToSuccess(['message' => 'Successfully logged out']);
    }

}
