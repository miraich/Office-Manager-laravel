<?php

namespace App\Http\Controllers;

use App\Enums\Roles;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    function register(RegisterRequest $request)
    {
        $user = User::create([
            'role_id' => Roles::GENERAL_DIRECTOR->value,
            'email' => $request->email,
            'name' => $request->name,
            'password' => $request->password,
        ]);
        if ($user->exists()) {
            return response()->json(['message' => 'Registered'], 201);
        }
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
