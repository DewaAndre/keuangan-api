<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDanaDaruratTable extends Migration
{
    public function up()
    {
        Schema::create('dana_darurat', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_user'); // nama yang umum dan sesuai

            $table->string('kategori');
            $table->integer('jumlah');
            $table->timestamps();

            // Foreign key harus cocok nama dan tipe kolomnya
            $table->foreign('id_user')->references('id_user')->on('users')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('dana_darurat');
    }
}

