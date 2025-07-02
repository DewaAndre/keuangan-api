<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TransaksiPremium extends Model
{
    protected $table = 'transaksi_premium';
    protected $primaryKey = 'id_transaksi';

    protected $fillable = [
        'id_user',
        'paket',
        'harga',
        'tanggal_mulai',
        'tanggal_berakhir',
    ];

    public $timestamps = true;
}
