<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Pemasukan;
use App\Models\Pengeluaran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class ApiController extends Controller
{
    public function register(Request $request)
    {
        $validated = $request->validate([
            'username' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required',
            'no_hp' => 'required',
            'tanggal_lahir' => 'required|date'
        ]);

        $validated['password'] = Hash::make($validated['password']);

        $user = User::create($validated);

        return response()->json([
            'status' => 'success',
            'data' => $user
        ]);
    }

    public function tambahPemasukan(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id_user',
            'jumlah' => 'required|numeric',
            'keterangan' => 'nullable|string',
        ]);

        $user = User::findOrFail($request->user_id);

        $user->pemasukan()->create([
            'jumlah' => $request->jumlah,
            'keterangan' => $request->keterangan,
        ]);

        $user->increment('saldo', $request->jumlah);

        return response()->json(['message' => 'Pemasukan berhasil ditambahkan.']);
    }

    public function tambahPengeluaran(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id_user',
            'jumlah' => 'required|numeric',
            'keterangan' => 'nullable|string',
        ]);

        $user = User::findOrFail($request->user_id);

        $user->pengeluaran()->create([
            'jumlah' => $request->jumlah,
            'keterangan' => $request->keterangan,
        ]);

        $user->decrement('saldo', $request->jumlah);

        return response()->json(['message' => 'Pengeluaran berhasil ditambahkan.']);
    }
}
