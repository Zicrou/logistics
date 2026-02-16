<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\V1\RegisterFormRequest;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\PersonalAccessToken;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Routing\Controllers\HasMiddleware;

class AuthController extends Controller
{
        // 
    public function register(RegisterFormRequest $request)
    {
        // Validate the request data
        $data = $request->validated();
        
        $data['password'] = Hash::make($data['password']);
        $emailExists = User::where('email', $data['email'])->exists();
        $phoneExists = User::where('phone', $data['phone'])->exists();
       
        if ($emailExists || $phoneExists) {
            return [
                "ok" => false,
                "message" => "Email or phone already exists",
                "status" => 409
            ];
        }
        $user = User::create($data);
        
        $token = $user->createToken($user->name)->plainTextToken;

        return [
            "ok" => true,
            "message" => "User registered successfully",
            "token" => $token,
            "user" => $user,
            "status" => 201
        ];
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => ['required','email'],
            'password' => ['required','string']
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json([
                "ok" => false,
                "message" => "Invalid credentials"
            ], 401);
        }

        $token = $user->createToken($user->name);
        $tokenFromRequest = PersonalAccessToken::findToken($token->plainTextToken);

        return response()->json([
            "ok" => true,
            "message" => "User logged in successfully",
            "token" => $token->plainTextToken,
            "token_info" => $tokenFromRequest,
            "user" => $user->name
        ], 200);
    }

    public function logout(Request $request){
        $request->user()->tokens()->delete();
        return ['message' => 'you are logged out',
            'loggedOut' => true,
        ];
        // $tokenString = $request->bearerToken(); // Just the token string, no "Bearer"
        // $tokenFromRequest = PersonalAccessToken::findToken($tokenString);
    }       
}
