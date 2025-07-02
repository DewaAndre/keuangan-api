<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'id_user',
        'order_id',
        'paket',
        'harga',
        'payment_method',
        'status',
        'paid_at',
    ];
}
