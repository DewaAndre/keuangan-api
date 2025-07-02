<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTransaksiPremiumTable extends Migration
{
    public function up()
    {
        Schema::create('transaksi_premium', function (Blueprint $table) {
            $table->id('id_transaksi');
            $table->unsignedBigInteger('id_user');
            $table->string('paket');
            $table->integer('harga');
            $table->dateTime('tanggal_mulai');
            $table->dateTime('tanggal_berakhir');
            $table->timestamps();

            $table->foreign('id_user')->references('id_user')->on('users')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('transaksi_premium');
    }
}

