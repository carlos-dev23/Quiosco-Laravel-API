<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegistroRequest;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function register(RegistroRequest $request){
        $data = $request->validated();
        try{
            $user = User::create([
                'name' => $data['name'],
                'email' => $data['email'],
                'password' => $data['password']
            ]);

            return [
                'token' => $user->createToken('token')->plainTextToken,
                'user' => $user
            ];
        }catch(Exception $e){
            return response()->json([
                'error' => 'Error al crear el usuario'
            ]);
        }
    }
    public function login(LoginRequest $request){
        $data = $request->validated();

        if(!Auth::attempt($data)){
            return response([
                'errors' => ['El email o la contraseña son incorrectos']
            ],422);
        }

        $user = Auth::user();
        return [
            'token' => $user->createToken('token')->plainTextToken,
            'user' => $user
        ];
    }
    public function logout(Request $request){
        $user = $request->user();
        $user->currentAccessToken()->delete();
        return [
            'user' => ''
        ];
    }
}
