<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_user');
            $table->string('order_id')->unique();
            $table->string('paket');
            $table->integer('harga');
            $table->string('payment_method');
            $table->string('status')->default('pending');
            $table->timestamp('paid_at')->nullable();
            $table->timestamps();

            $table->foreign('id_user')->references('id_user')->on('users')->onDelete('cascade');
        });
    }


    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('payments');
    }
};
