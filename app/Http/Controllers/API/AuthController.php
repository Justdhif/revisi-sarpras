<?php

namespace App\Http\Controllers\API;

use App\Models\User;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required']
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['Email atau password salah.'],
            ]);
        }

        if ($user->role !== 'user') {
            return response()->json(['message' => 'Akses hanya untuk pengguna.'], 403);
        }

        $token = $user->createToken('flutter-user-token')->plainTextToken;

        ActivityLog::create([
            'user_id' => $user->id,
            'action' => 'Login',
            'description' => $user->username . ' login melalui API',
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        return response()->json([
            'user' => $user,
            'token' => $token,
        ]);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        ActivityLog::create([
            'user_id' => $request->user()->id,
            'action' => 'Logout',
            'description' => $request->user()->username . ' logout melalui API',
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        return response()->json(['message' => 'Berhasil logout']);
    }

    public function profile(Request $request)
    {
        $user = $request->user();
        return response()->json([
            'user' => $user,
        ]);
    }
}
