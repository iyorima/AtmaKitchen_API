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
        Schema::create('notifikasis', function (Blueprint $table) {
            $table->bigIncrements('id_notifikasi');
            $table->string('judul');
            $table->string('deskripsi')->nullable();
            $table->unsignedBigInteger('id_akun')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('id_akun')->references('id_akun')->on('akuns');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notifikasis');
    }
};
