<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use App\Models\User;
use App\Models\TransaksiPremium;

class PremiumController extends Controller
{
    public function beliPaket(Request $request)
    {
        $request->validate([
            'id_user' => 'required|exists:users,id_user',
            'paket' => 'required|string',
            'harga' => 'required|numeric', // pastikan ini ada
        ]);

        $tanggal_mulai = now();
        $tanggal_berakhir = $tanggal_mulai->copy()->addMonth();

        TransaksiPremium::create([
            'id_user' => $request->id_user,
            'paket' => $request->paket,
            'harga' => $request->harga,
            'tanggal_mulai' => $tanggal_mulai,
            'tanggal_berakhir' => $tanggal_berakhir,
        ]);

        return response()->json(['message' => 'Transaksi berhasil'], 201);
    }

}

