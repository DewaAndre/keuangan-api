<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Pemasukan;
use App\Models\Pengeluaran;
use App\Models\Hutang;
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
            'id_user' => 'required|exists:users,id_user',
            'jumlah' => 'required|numeric',
            'keterangan' => 'nullable|string',
        ]);

        $user = User::findOrFail($request->id_user);

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

    public function getUsers()
    {
        $users = \App\Models\User::select([
            'id_user',
            'username',
            'email',
            'password',
            'no_hp',
            'tanggal_lahir',
            'saldo',
            'status',
            'created_at',
            'updated_at'
        ])->get();

        return response()->json([
            'status' => 'success',
            'data' => $users
        ]);
    }



    public function tambahHutang(Request $request)
    {
        $request->validate([
            'id_user' => 'required|exists:users,id_user',
            'jumlah' => 'required|numeric|min:1',
            'keterangan' => 'nullable|string'
        ]);

        $user = User::where('id_user', $request->id_user)->first();

        if ($user->saldo < $request->jumlah) {
            return response()->json([
                'status' => 'error',
                'message' => 'Saldo tidak cukup untuk melakukan hutang.'
            ], 400);
        }

        $user->saldo -= $request->jumlah;
        $user->save();

        $hutang = Hutang::create([
            'id_user' => $request->id_user, // ✅ ganti user_id ke id_user
            'jumlah' => $request->jumlah,
            'keterangan' => $request->keterangan
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Hutang berhasil ditambahkan dan saldo dikurangi.',
            'data' => $hutang,
            'sisa_saldo' => $user->saldo
        ]);
    }


    public function getKeuangan()
    {
        $users = User::all();

        $data = $users->map(function ($user) {
            $totalPemasukan = Pemasukan::where('id_user', $user->id_user)->sum('jumlah');
            $totalPengeluaran = Pengeluaran::where('id_user', $user->id_user)->sum('jumlah');
            $totalHutang = Hutang::where('id_user', $user->id_user)->sum('jumlah');

            return [
                'id_user'     => $user->id_user,
                'username'    => $user->username,
                'pemasukan'   => $totalPemasukan,
                'pengeluaran' => $totalPengeluaran,
                'hutang'      => $totalHutang,
            ];
        });

        return response()->json([
            'status' => 'success',
            'data' => $data
        ]);
    }

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

        // Generate token (bebas, bisa pakai Laravel Sanctum, JWT, atau string acak)
        $token = bin2hex(random_bytes(40)); // 80 karakter random

        $user->token = $token;
        $user->save();

        return response()->json([
            'status' => 'success',
            'message' => 'Login berhasil.',
            'token' => $token,
            'user' => [
                'id_user' => $user->id_user,
                'username' => $user->username,
                'email' => $user->email
            ]
        ]);
    }


}
