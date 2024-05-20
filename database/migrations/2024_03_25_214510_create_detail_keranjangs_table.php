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
        Schema::create('detail_keranjangs', function (Blueprint $table) {
            $table->bigIncrements('id_detail_keranjang');
            $table->unsignedBigInteger('id_keranjang');
            $table->foreign('id_keranjang')->references('id_keranjang')->on('keranjangs')->onDelete('cascade')->onUpdate('cascade');
            $table->unsignedBigInteger('id_produk')->nullable();
            $table->foreign('id_produk')->references('id_produk')->on('produks')->onDelete('cascade')->onUpdate('cascade');
            $table->unsignedBigInteger('id_produk_hampers')->nullable();
            $table->foreign('id_produk_hampers')->references('id_produk_hampers')->on('produk_hampers')->onDelete('cascade')->onUpdate('cascade');
            $table->integer('jumlah');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('detail_keranjangs');
    }
};
