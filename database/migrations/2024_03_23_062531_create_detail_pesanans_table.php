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
        Schema::create('detail_pesanans', function (Blueprint $table) {
            $table->bigIncrements('id_detail_pesanan');
            $table->string('id_pesanan', 15);
            $table->foreign('id_pesanan')->references('id_pesanan')->on('pesanans')->onDelete('cascade')->onUpdate('cascade');
            $table->unsignedBigInteger('id_produk')->nullable();
            $table->foreign('id_produk')->references('id_produk')->on('produks')->onDelete('cascade')->onUpdate('cascade');
            $table->unsignedBigInteger('id_produk_hampers')->nullable();
            $table->foreign('id_produk_hampers')->references('id_produk_hampers')->on('produk_hampers')->onDelete('cascade')->onUpdate('cascade');
            $table->string('kategori', 50);
            $table->string('nama_produk', 255);
            $table->double('harga', 15, 2);
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
        Schema::dropIfExists('detail_pesanans');
    }
};
