<?php

namespace App\Http\Controllers;

use App\Http\Requests\VerifyEmailFormRequest;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;

class VerifyEmailController extends Controller
{
    public function verifyEmail(VerifyEmailFormRequest $request)
    {
        $user = $request->user();
        $token = $user->email_verification_token;
        if (hash('sha256', $request->email_verification_token) === $token) {
            $user->markEmailAsVerified();
            return response()->json(['message' => "Verified successfully"]);
        }
        return response()->json(['message' => "Invalid verification token"], 409);
    }

    public function resendEmail(Request $request)
    {

    }
}
