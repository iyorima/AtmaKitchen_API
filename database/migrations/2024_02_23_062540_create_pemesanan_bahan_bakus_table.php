<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('pemesanan_bahan_bakus', function (Blueprint $table) {
            $table->bigIncrements('id_pemesanan_bahan_baku');
            $table->unsignedBigInteger('id_bahan_baku');
            $table->string('nama', 255);
            $table->string('satuan', 15);
            $table->double('jumlah');
            $table->double('harga_beli', 15, 2);
            $table->double('total', 15, 2);
            $table->timestamps();
            $table->softDeletes();
            $table->foreign('id_bahan_baku')->references('id_bahan_baku')->on('bahan_bakus')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pemesanan_bahan_bakus');
    }
};
