<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;

class UserAuthController extends Controller
{
    private function verifyRecaptcha(?string $token): bool
    {
        // Skip verification on local/non-production environments for development convenience
        if (app()->environment('local') || config('app.env') !== 'production') {
            return true;
        }
        $secret = config('services.recaptcha.secret', env('RECAPTCHA_SECRET'));
        if (empty($secret)) {
            // If no secret configured, skip verification (treat as passed)
            return true;
        }
        if (empty($token)) return false;

        try {
            $resp = Http::asForm()->post('https://www.google.com/recaptcha/api/siteverify', [
                'secret' => $secret,
                'response' => $token,
            ]);
            if (!$resp->ok()) return false;
            $json = $resp->json();
            return (bool)($json['success'] ?? false);
        } catch (\Throwable $e) {
            return false;
        }
    }

    public function register(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6|confirmed',
            'recaptcha_token' => 'nullable|string',
        ]);

        if (!$this->verifyRecaptcha($validated['recaptcha_token'] ?? null)) {
            return response()->json([
                'message' => 'Captcha verification failed'
            ], 422);
        }

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => $validated['password'],
        ]);

        $token = $user->createToken('web-token')->plainTextToken;

        return response()->json([
            'message' => 'Registered',
            'user' => $user,
            'token' => $token,
            'token_type' => 'Bearer',
        ], 201);
    }

    public function login(Request $request)
    {
        $validated = $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
            'device_name' => 'nullable|string',
            'recaptcha_token' => 'nullable|string',
        ]);

        if (!$this->verifyRecaptcha($validated['recaptcha_token'] ?? null)) {
            return response()->json([
                'message' => 'Captcha verification failed'
            ], 422);
        }

        $user = User::where('email', $validated['email'])->first();
        if (! $user || ! Hash::check($validated['password'], $user->password)) {
            return response()->json([
                'message' => 'Invalid credentials'
            ], 422);
        }

        $token = $user->createToken($validated['device_name'] ?? 'web-token')->plainTextToken;

        return response()->json([
            'user' => $user,
            'token' => $token,
            'token_type' => 'Bearer'
        ]);
    }

    public function me(Request $request)
    {
        return response()->json(['user' => $request->user()]);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        return response()->json(['message' => 'Logged out']);
    }
}
