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
        Schema::create('notifikasi_reads', function (Blueprint $table) {
            $table->bigIncrements('id_notifikasi_read');
            $table->unsignedBigInteger('id_akun');
            $table->unsignedBigInteger('id_notifikasi');
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('id_akun')->references('id_akun')->on('akuns');
            $table->foreign('id_notifikasi')->references('id_notifikasi')->on('notifikasis');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notifikasi_reads');
    }
};
