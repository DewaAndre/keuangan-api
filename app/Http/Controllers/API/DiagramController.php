<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Pemasukan;
use App\Models\Pengeluaran;

class DiagramController extends Controller
{
    public function getDiagram(Request $request)
    {
        $id_user = $request->query('id_user');

        $pemasukan = Pemasukan::where('id_user', $id_user)
            ->get()
            ->groupBy('keterangan')
            ->map(function ($items) {
                return [
                    'keterangan' => $items->first()->keterangan,
                    'jumlah' => $items->sum('jumlah'),
                ];
            })->values();

        $pengeluaran = Pengeluaran::where('id_user', $id_user)
            ->get()
            ->groupBy('keterangan')
            ->map(function ($items) {
                return [
                    'keterangan' => $items->first()->keterangan,
                    'jumlah' => $items->sum('jumlah'),
                ];
            })->values();

        return response()->json([
            'pemasukan' => $pemasukan,
            'pengeluaran' => $pengeluaran
        ]);
    }
}
