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
        Schema::create('produk_hampers', function (Blueprint $table) {
            $table->bigIncrements('id_produk_hampers');
            $table->unsignedBigInteger('id_produk');
            $table->string('nama', 255);
            $table->string('satuan', 15);
            $table->integer('jumlah');
            $table->timestamps();
            $table->softDeletes();
            $table->foreign('id_produk')->references('id_produk')->on('produks')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('produk_hampers');
    }
};
