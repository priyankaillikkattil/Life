<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;


class AuthController extends Controller
{
    /**     User Registration   **/
    public function register(Request $request)
    {
        $rules = [      'name'      => 'required|string|max:255|min:3',
                        'email'     => 'required|string|email|max:255|unique:users',
                        'password'  => 'required|string|min:6|confirmed',
                        'type'      => 'nullable|in:customer,admin',
                 ];
        $validator          = Validator::make($request->all(), $rules);    
        if ($validator->fails()) { 
            return response()->json($validator->errors(), 422);
        } 
        $userType = 'customer'; 
        if (auth()->check() && auth()->user()->user_type === 'super_admin' && isset($request->type) && $request->type == 'admin') {
            $userType = 'admin'; 
        } 
        $user = User::create([
            'name'      => $request->name,
            'email'     => $request->email,
            'password'  => Hash::make($request->password),
            'user_type' => $userType, 
        ]);
        $token = $user->createToken('auth_token')->plainTextToken;
        return response()->json([
            'user'  => $user,
            'token' => $token
        ], 201);
    }

    /**  User Login **/
    public function login(Request $request)
    {
        $request->validate([
            'email'    => 'required|string|email',
            'password' => 'required|string',
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json(['message' => 'Invalid login credentials'], 401);
        }

        // Create API Token
        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'user'  => $user,
            'token' => $token
        ]);
    }


    /**   Get Authenticated User Details    **/
    public function userProfile(Request $request)
    {
        if (auth()->check()) {
            return response()->json([
                'user' => auth()->user(),
            ]);
        }
        return response()->json([
            'message' => 'Not logged in',
        ], 401); 
    }

    /** Logout User **/
    public function logout(Request $request)
    {
        $request->user()->tokens()->delete();

        return response()->json(['message' => 'Successfully logged out']);
    }
}
