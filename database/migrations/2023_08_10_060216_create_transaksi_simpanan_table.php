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
        Schema::create('transaksi_simpanan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('anggota_id')->constrained('anggotas');
            $table->foreignId('transaksi_harian_biaya_id')->constrained('transaksi_harian_biayas');
            $table->date('tanggal_awal');
            $table->date('tanggal_akhir');
            $table->integer('lama_simpanan');
            $table->double('simpanan_pinjaman');
            $table->double('simpanan_bulanan');
            $table->integer('status');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transaksi_simpanan');
    }
};
