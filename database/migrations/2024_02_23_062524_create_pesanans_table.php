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
        Schema::create('pesanans', function (Blueprint $table) {
            $table->string('id_pesanan', 15)->primary();
            // $table->string('id_metode_pembayaran');
            $table->unsignedBigInteger('id_metode_pembayaran');
            $table->foreign('id_metode_pembayaran')->references('id_metode_pembayaran')->on('metode_pembayarans');
            $table->unsignedBigInteger('id_pelanggan');
            $table->foreign('id_pelanggan')->references('id_pelanggan')->on('pelanggans')->onDelete('cascade')->onUpdate('cascade');
            $table->date('tgl_order');
            $table->integer('total_diskon_poin');
            $table->double('total_pesanan', 15, 2);
            $table->double('total_setelah_diskon', 15, 2);
            $table->double('total_dibayarkan')->nullable();
            $table->double('total_tip');
            $table->string('jenis_pengiriman');
            $table->string('bukti_pembayaran')->nullable();
            $table->timestamp('verified_at')->nullable();
            $table->timestamp('accepted_at')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pesanans');
    }
};
