<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Wishlist;

class WishlistController extends Controller
{
    public function index(Request $request)
    {
        $id_user = $request->query('id_user');
        $wishlists = Wishlist::where('id_user', $id_user)->get();

        return response()->json([
            'status' => 'success',
            'data' => $wishlists
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_user' => 'required',
            'nama' => 'required|string',
            'jumlah' => 'required|numeric',
        ]);

        $wishlist = Wishlist::create($request->all());

        return response()->json([
            'status' => 'success',
            'message' => 'Wishlist berhasil ditambahkan',
            'data' => $wishlist
        ]);
    }
}

