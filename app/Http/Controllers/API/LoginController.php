<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class LoginController extends Controller
{
    // Endpoint untuk testing
    public function test()
    {
        return response()->json([
            'status' => 'success',
            'data' => null,
            'message' => 'Test successful',
            'errors' => null,
        ]);
    }

    /**
     * Login user dan generate token Sanctum.
     */
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json([
                'status' => 'error',
                'message' => 'Email atau password salah.'
            ], 401);
        }

        // Buat token Sanctum
        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'status' => 'success',
            'message' => 'Login berhasil.',
            'access_token' => $token,
            'token_type' => 'Bearer',
            'user' => [
                'id_user' => $user->id_user,
                'username' => $user->username,
                'email' => $user->email
            ]
        ]);
    }


    /**
     * Logout user dan hapus token Sanctum yang aktif.
     */
    public function logout(Request $request)
    {
        // Hapus token aktif (token saat ini)
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Berhasil logout.',
            'data' => null,
            'errors' => null,
        ]);
    }
}
