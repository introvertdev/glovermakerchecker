<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function register(Request $request) {
        $fields = $request->validate([
            'email' =>  'required|string|unique:users,email',
            'password'  =>  'required|string|confirmed'
        ]);

        //create user
        $user = User::create([
            'email' =>  $fields['email'],
            'password'  =>  bcrypt($fields['password'])
        ]);

        //generate auth token
        $token = $user->createToken('gloverapp')->plainTextToken;

        //create response
        $response = [
            'message' => 'Registration successful',
            'user'  =>  $user,
            'token' =>  $token
        ];

        return response($response, 201);
    }

    public function logout(Request $request) {
        //destroy active user token
        auth()->user()->tokens()->delete();

        //create response
        return response([
            'message' => 'Logged out successfully'
        ]);
    }

    public function login(Request $request) {
        $fields = $request->validate([
            'email' =>  'required|string',
            'password'  =>  'required|string'
        ]);

        //check if email exists
        $user = User::where('email', $fields['email'])->first();

        //check password
        if(!$user || !Hash::check($fields['password'], $user->password)){
            return response([
                'message' => 'Invalid login credentials'
            ], 401);
        }

        //generate auth token
        $token = $user->createToken('gloverapp')->plainTextToken;

        //create response
        $response = [
            'message' => 'Logged in successfully',
            'user'  =>  $user,
            'token' =>  $token
        ];

        return response($response, 201);
    }

}
