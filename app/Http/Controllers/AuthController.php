<?php

namespace App\Http\Controllers;

use App\Enums\Roles;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Models\User;
use App\Notifications\VerificationMail;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    function register(RegisterRequest $request): JsonResponse
    {
        if (User::where('email', $request->email)->exists()) {
            return response()->json(['message' => 'User already exists.'], 409);
        }
        $email_token = \Illuminate\Support\Str::random(64);
        $user = User::create([
            'email_verification_token' => hash('sha256', $email_token),
            'role_id' => Roles::GENERAL_DIRECTOR->value,
            'email' => $request->email,
            'name' => $request->username,
            'password' => $request->password,
        ]);
        $user->notify(new VerificationMail($email_token));
        $token = $user->createToken('auth_token')->plainTextToken;
        return response()->json([
            'message' => 'Registered',
            'token' => $token,
        ], 201);
    }

    function login(LoginRequest $request): JsonResponse
    {
        if (Auth::attempt($request->only('email', 'password'))) {
            $user = Auth::user();
            $token = $user->createToken('auth_token')->plainTextToken;
            return response()->json([
                'token' => $token
            ], 200);
        }
        return response()->json(['error' => 'Invalid credentials'], 401);
    }

    function logout(Request $request): Response
    {
//        $request->user()->currentAccessToken()->delete();
        $request->user()->tokens()->delete();
        return response()->noContent();
    }
}
