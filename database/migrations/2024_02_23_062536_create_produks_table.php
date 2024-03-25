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
        Schema::create('produks', function (Blueprint $table) {
            $table->bigIncrements('id_produk');
            $table->unsignedBigInteger('id_kategori');
            $table->string('id_penitip', 15)->nullable();
            $table->string('nama', 255);
            $table->integer('kapasitas');
            $table->string('ukuran', 15);
            $table->double('harga_jual', 15, 2);
            $table->timestamps();
            $table->softDeletes();
            $table->foreign('id_kategori')->references('id_kategori')->on('kategori_produks')->onDelete('cascade');
            $table->foreign('id_penitip')->references('id_penitip')->on('penitips');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('produks');
    }
};
