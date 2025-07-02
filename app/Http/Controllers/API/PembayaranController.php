<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;


class PembayaranController extends Controller
{
    public function bayar(Request $request)
    {
        $request->validate([
            'user_id' => 'required|integer',
            'order_id' => 'required|string',
            'paket' => 'required|string',
            'harga' => 'required|integer',
            'payment_method' => 'required|string',
        ]);

        $userId = $request->user_id;
        $paket = $request->paket;
        $harga = $request->harga;

        $now = now();
        $tanggalMulai = $now;
        $tanggalBerakhir = match(true) {
            str_contains(strtolower($paket), 'minggu') => $now->copy()->addWeek(),
            str_contains(strtolower($paket), 'tahun') => $now->copy()->addYear(),
            default => $now->copy()->addMonth(),
        };

        // Tambahkan ke tabel payments
        DB::table('payments')->insert([
            'id_user' => $userId,
            'order_id' => $request->order_id,
            'paket' => $paket,
            'harga' => $harga,
            'payment_method' => $request->payment_method,
            'status' => 'paid',
            'paid_at' => $now,
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        // Tambahkan ke transaksi_premium
        DB::table('transaksi_premium')->insert([
            'id_user' => $userId,
            'paket' => $paket,
            'harga' => $harga,
            'tanggal_mulai' => $tanggalMulai,
            'tanggal_berakhir' => $tanggalBerakhir,
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        // Ubah status user jadi 'premium'
        DB::table('users')->where('id_user', $userId)->update([
            'status' => 'premium',
            'updated_at' => $now,
        ]);

        return response()->json(['success' => true, 'message' => 'Pembayaran berhasil']);
    }

}
