<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Auth;
use Illuminate\Http\Request;
use Validator;

class ApiController extends Controller
{
    public function register(Request $request) {
        $validateUser = Validator::make($request->all(), [
            'name'      => 'required',
            'email'     => 'required|email|unique:users',
            'password'  => 'required|min:8'
        ]);

        if($validateUser->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'validation error',
                'errors' => $validateUser->errors()
            ], 401);
        }

        $user = User::create([
            'name'      => $request->name,
            'email'     => $request->email,
            'password'  => bcrypt($request->password)
        ]);

        return response()->json([
            'success' => true,
            'user'    => $user,
            'token' => $user->createToken("API TOKEN")->plainTextToken  
        ], 201);
    }

    public function login(Request $request) {
        $validateUser = Validator::make($request->all(), [
            'email'     => 'required|email',
            'password'  => 'required'
        ]);

        if($validateUser->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'validation error',
                'errors' => $validateUser->errors()
            ], 401);
        }

        if (!Auth::attempt($request->only(['email', 'password']))) {
            return response()->json([
                'status' => false,
                'message' => 'Email & Password does not match with our record.',
            ], 401);
        }

        $user = User::where('email', $request->email)->first();
        return response()->json([
            'status' => true,
            'message' => 'User successfully logged in.',
            'user'    => $user,
            'token'   => $user->createToken("API TOKEN")->plainTextToken
        ], 200);
    }

    public function profile() {
        $user = Auth::user();
        return response()->json([
            'status' => true,
            'message' => 'User profile fetched successfully.',
            'user'    => $user
        ], 200);
    }
}
