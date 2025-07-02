<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\DanaDarurat;

class DanaDaruratController extends Controller
{
    // GET: /api/dana-darurat
    public function index($id_user)
    {
        $data = DanaDarurat::where('id_user', $id_user)->get();

        return response()->json([
            'status' => 'success',
            'data' => $data
        ]);
    }


    // POST: /api/dana-darurat
    public function store(Request $request)
    {
        $request->validate([
            'id_user' => 'required|exists:users,id_user',
            'jumlah' => 'required|numeric',
            'kategori' => 'nullable|string',
        ]);

        $dana = DanaDarurat::create([
            'id_user' => $request->id_user,
            'jumlah' => $request->jumlah,
            'kategori' => $request->kategori,
        ]);

        return response()->json([
            'status' => 'success',
            'data' => $dana
        ], 201);
    }

}
