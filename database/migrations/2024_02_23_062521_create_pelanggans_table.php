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
        Schema::create('pelanggans', function (Blueprint $table) {
            $table->bigIncrements('id_pelanggan');
            $table->unsignedBigInteger('id_akun'); // Add unique for one-to-one relationship
            $table->foreign('id_akun')->references('id_akun')->on('akuns')->onDelete('cascade')->constrained(); // Constrained for one-to-one relationship
            $table->string('nama', 255);
            $table->date('tgl_lahir')->nullable();
            $table->string('telepon', 15)->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pelanggans');
    }
};
