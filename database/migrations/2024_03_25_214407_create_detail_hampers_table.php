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
        Schema::create('detail_hampers', function (Blueprint $table) {
            $table->bigIncrements('id_detail_hampers');
            $table->unsignedBigInteger('id_produk_hampers');
            $table->foreign('id_produk_hampers')->references('id_produk_hampers')->on('produk_hampers')->onDelete('cascade');
            $table->unsignedBigInteger('id_produk');
            $table->foreign('id_produk')->references('id_produk')->on('produks')->onDelete('cascade');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('detail_hampers');
    }
};
