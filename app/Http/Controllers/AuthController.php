<?php

namespace App\Http\Controllers;

use App\Enums\Roles;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Models\User;
use App\Notifications\VerificationMail;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Psy\Util\Str;

class AuthController extends Controller
{
    function register(RegisterRequest $request)
    {
        if (User::where('email', $request->email)->exists()) {
            return response()->json(['message' => 'User already exists.'], 409);
        }
        $token = \Illuminate\Support\Str::random(64);
        $user = User::create([
            'email_verification_token' => hash('sha256', $token),
            'role_id' => Roles::GENERAL_DIRECTOR->value,
            'email' => $request->email,
            'name' => $request->name,
            'password' => $request->password,
        ]);
        $user->notify(new VerificationMail($token));
        return response()->json(['message' => 'Registered'], 201);
    }

    function login(LoginRequest $request): JsonResponse
    {
        if (Auth::attempt($request->only('email', 'password'))) {
            $user = Auth::user();
            $token = $user->createToken('auth_token')->plainTextToken;
            return response()->json([
                'user' => $user,
                'token' => $token
            ], 200);
        }

        return response()->json(['error' => 'Invalid credentials'], 401);
    }

    function logout(Request $request): Response
    {
        $request->user()->currentAccessToken()->delete();
        return response()->noContent();
    }
}
