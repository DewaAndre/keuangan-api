<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Hutang extends Model
{
    protected $fillable = ['user_id', 'jumlah', 'keterangan'];

    public function user() {
        return $this->belongsTo(User::class, 'user_id');
    }
}

