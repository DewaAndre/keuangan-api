<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;

class UserController extends Controller
{
    public function show($id)
    {
        $user = User::find($id);

        if (!$user) {
            return response()->json([
                'status' => 'error',
                'message' => 'User tidak ditemukan',
            ], 404);
        }

        return response()->json([
            'status' => 'success',
            'data' => $user
        ]);
    }

    public function update(Request $request, $id)
    {
        $user = User::find($id);

        if (!$user) {
            return response()->json([
                'status' => 'error',
                'message' => 'User tidak ditemukan',
            ], 404);
        }

        $request->validate([
            'username' => 'required|string',
            'email' => 'required|email',
            'no_hp' => 'required',
            'tanggal_lahir' => 'required|date',
        ]);

        $user->update($request->all());

        return response()->json([
            'status' => 'success',
            'message' => 'User berhasil diupdate',
            'data' => $user
        ]);
    }

    public function resetPassword(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string|min:6',
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return response()->json(['message' => 'User tidak ditemukan'], 404);
        }

        $user->password = bcrypt($request->password);
        $user->save();

        return response()->json(['message' => 'Password berhasil direset'], 200);
    }


}
