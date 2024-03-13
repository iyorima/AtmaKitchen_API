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
        Schema::create('pembayarans', function (Blueprint $table) {
            $table->bigIncrements('id_pembayaran');
            $table->unsignedBigInteger('id_pesanan');
            $table->foreign('id_pesanan')->references('id_pesanan')->on('pesanans')->onDelete('cascade');
            $table->unsignedBigInteger('id_metode_pembayaran');
            $table->foreign('id_metode_pembayaran')->references('id_metode_pembayaran')->on('metode_pembayarans')->onDelete('cascade');
            $table->double('total_dibayarkan', 15, 2);
            $table->double('total_tip', 15, 2);
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
        Schema::dropIfExists('pembayarans');
    }
};
