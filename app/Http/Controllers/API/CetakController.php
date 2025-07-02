<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CetakController extends Controller
{
    public function cetak(Request $request)
    {
        $id_user = $request->query('id_user');

        $pemasukan = DB::table('pemasukans')->where('id_user', $id_user)->get();
        $pengeluaran = DB::table('pengeluarans')->where('id_user', $id_user)->get();
        $dana_darurat = DB::table('dana_darurat')->where('id_user', $id_user)->sum('jumlah');

        $pemasukan_total = $pemasukan->sum('jumlah');
        $pengeluaran_total = $pengeluaran->sum('jumlah');

        // ambil data user
        $user = DB::table('users')->where('id_user', $id_user)->first();

        if (!$user) {
            return response()->json([
                'status' => 'error',
                'message' => 'User tidak ditemukan'
            ], 404);
        }

        return response()->json([
            'status' => 'success',
            'data' => [
                'saldo' => $user->saldo,
                'status' => $user->status, // ✅ ini penting
                'pemasukan_total' => $pemasukan_total,
                'pengeluaran_total' => $pengeluaran_total,
                'dana_darurat' => $dana_darurat,
                'pemasukan' => $pemasukan,
                'pengeluaran' => $pengeluaran
            ]
        ]);
    }


}
