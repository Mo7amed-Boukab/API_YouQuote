<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;


class AuthController extends Controller
{
    public function register(RegisterRequest $request){

        $request->validated();
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password) 
        ]);
        
        return response()->json(['message' => 'User Registered successfully', 'user' => $user], 201);
    }


    public function login(LoginRequest $request){

        $request->validated();
        
        if(!Auth::attempt($request->only('email','password'))){
            return response()->json(['message' => 'Email or Password incorrect'], 401);
        }

        $user = User::where('email', $request->email)->firstOrFail();
        $token = $user->createToken('auth_Token')->plainTextToken;

        return response()->json([
            'message' => 'You Logged In successfully', 
            'user' => $user, 
            'token' => $token
        ], 200);
    }


    public function logout(Request $request){
       $request->user()->currentAccessToken()->delete();
       return response()->json(['message' => 'You Logged out successfully'], 200);
    }
}
