<?php

namespace App\Http\Controllers;

use App\Http\Requests\RegistroRequest;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;

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
    public function login(Request $request){

    }
    public function logout(Request $request){

    }
}
