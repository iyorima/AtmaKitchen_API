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
        Schema::create('penarikan_saldos', function (Blueprint $table) {
            $table->bigIncrements('id_penarikan_saldo');
            $table->unsignedBigInteger('id_akun');
            $table->foreign('id_akun')->references('id_akun')->on('akuns')->onDelete('cascade');
            $table->double('jumlah_penarikan', 10, 2);
            $table->timestamp('transfer_at')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('penarikan_saldos');
    }
};
