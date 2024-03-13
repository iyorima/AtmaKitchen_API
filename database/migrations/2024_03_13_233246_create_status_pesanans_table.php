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
        Schema::create('status_pesanans', function (Blueprint $table) {
            $table->bigIncrements('id_status_pesanan');
            $table->string('id_pesanan', 15);
            $table->foreign('id_pesanan')->references('id_pesanan')->on('pesanans')->onDelete('cascade');
            $table->unsignedBigInteger('id_karyawan')->nullable();
            $table->foreign('id_karyawan')->references('id_karyawan')->on('karyawans')->onDelete('set null');
            $table->string('status', 50);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('status_pesanans');
    }
};
