<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('penjualan', function (Blueprint $table) {
            $table->id('id_penjualan');
            $table->unsignedBigInteger('id_master');
            $table->string('nama_kolom', 255);
            $table->string('isi_kolom', 255);

            $table->foreign('id_master')->references('id_master')->on('data_master')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('penjualan');
    }
};
