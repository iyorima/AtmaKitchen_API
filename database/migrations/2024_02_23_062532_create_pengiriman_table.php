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
        Schema::create('pengirimen', function (Blueprint $table) {
            $table->bigIncrements('id_pengiriman');
            $table->string('id_pesanan', 15);
            $table->foreign('id_pesanan')->references('id_pesanan')->on('pesanans')->onDelete('cascade')->onUpdate('cascade');
            // $table->integer('id_kategori_pengiriman');
            $table->unsignedBigInteger('id_kurir')->nullable();
            $table->foreign('id_kurir')->references('id_karyawan')->on('karyawans')->onDelete('cascade')->onUpdate('cascade');
            $table->double('jarak', 15, 2)->nullable();
            $table->double('harga', 15, 2)->nullable();
            $table->string('nama', 255);
            $table->string('telepon', 15);
            $table->string('alamat');
            $table->timestamp('verified_at')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pengiriman');
    }
};
