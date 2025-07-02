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

    // Jika primary key bukan "id"
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
        'status',
        'remember_token' // hanya jika digunakan
    ];

    // Menyembunyikan field sensitif saat response JSON
    protected $hidden = [
        'password',
        'remember_token'
    ];

    // Konversi otomatis tipe data
    protected $casts = [
        'email_verified_at' => 'datetime',
        'tanggal_lahir' => 'date',
        'saldo' => 'double'
    ];

    // Relasi ke tabel pemasukan
    public function pemasukan()
    {
        return $this->hasMany(Pemasukan::class, 'id_user', 'id_user');
    }

    // Relasi ke tabel pengeluaran
    public function pengeluaran()
    {
        return $this->hasMany(Pengeluaran::class, 'id_user', 'id_user');
    }

    // Relasi ke tabel hutang
    public function hutang()
    {
        return $this->hasMany(Hutang::class, 'id_user', 'id_user');
    }
}
