<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class HomeController extends Controller
{
    public function index(Request $request)
    {
        $userId = $request->query('id_user');

        // Ambil user beserta relasi
        $user = User::with(['pemasukan', 'pengeluaran', 'hutang'])->find($userId);

        if (!$user) {
            return response()->json([
                'status' => 'error',
                'message' => 'User not found'
            ], 404);
        }

        return response()->json([
            'status' => 'success',
            'data' => [
                'id_user' => $user->id,
                'username' => $user->username,
                'saldo' => $user->saldo,
                'pemasukan' => $user->pemasukan,
                'pengeluaran' => $user->pengeluaran,
                'hutang' => $user->hutang,
            ]
        ]);
    }
}
