<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DanaDarurat extends Model
{
    protected $table = 'dana_darurat'; // pastikan nama tabel sesuai di database

    protected $fillable = [
        'id_user',
        'jumlah',
        'kategori',
        'keterangan'
    ];
}
