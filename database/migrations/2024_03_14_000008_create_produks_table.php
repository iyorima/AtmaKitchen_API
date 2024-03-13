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
            $table->unsignedBigInteger('id_penitip')->nullable();
            $table->string('nama', 255);
            $table->integer('kapasitas');
            $table->double('harga_jual', 15, 2);
            $table->timestamps();
            $table->softDeletes();
            $table->foreign('id_kategori')->references('id_kategori')->on('kategoris')->onDelete('cascade');
            $table->foreign('id_penitip')->references('id_penitip')->on('penitips')->onDelete('set null');
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
