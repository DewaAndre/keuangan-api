<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Hutang extends Model
{
    use HasFactory;

    protected $table = 'hutangs';

    protected $fillable = [
        'id_user',
        'jumlah',
        'keterangan',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user', 'id_user');
    }

}
