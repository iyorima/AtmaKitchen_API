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
        Schema::create('saldo_pelanggans', function (Blueprint $table) {
            $table->bigIncrements('id_saldo_pelanggan');
            $table->unsignedBigInteger('id_akun');
            $table->foreign('id_akun')->references('id_akun')->on('akuns')->onDelete('cascade');
            $table->string('id_pesanan', 15);
            $table->foreign('id_pesanan')->references('id_pesanan')->on('pesanans')->onDelete('set null');
            $table->double('saldo', 15, 2)->default(0);
            $table->double('total_saldo', 15, 2)->default(0);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('saldo_pelanggans');
    }
};
