<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use App\Models\Pemasukan;
use App\Models\Pengeluaran;
use App\Models\Hutang;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $primaryKey = 'id_user';
    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = [
        'username',
        'name',
        'email',
        'password',
        'no_hp',
        'tanggal_lahir',
        'saldo',
        'status'
    ];

    protected $hidden = ['password', 'remember_token'];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'tanggal_lahir' => 'date',
        'saldo' => 'double'
    ];

    public function pemasukan()
    {
        return $this->hasMany(Pemasukan::class, 'user_id', 'id_user');
    }

    public function pengeluaran()
    {
        return $this->hasMany(Pengeluaran::class, 'user_id', 'id_user');
    }

    public function hutang()
    {
        return $this->hasMany(Hutang::class, 'user_id', 'id_user');
    }
}
