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
        Schema::create('resep_produks', function (Blueprint $table) {
            $table->bigIncrements('id_resep_produk');
            $table->unsignedBigInteger('id_produk');
            $table->unsignedBigInteger('id_bahan_baku');
            $table->string('satuan', 15);
            $table->double('jumlah');
            $table->timestamps();
            $table->softDeletes();
            $table->foreign('id_produk')->references('id_produk')->on('produks')->onDelete('cascade');
            $table->foreign('id_bahan_baku')->references('id_bahan_baku')->on('bahan_bakus')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('resep_produks');
    }
};
