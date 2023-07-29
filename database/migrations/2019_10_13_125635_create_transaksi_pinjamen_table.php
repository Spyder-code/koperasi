<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTransaksiPinjamenTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transaksi_pinjamen', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('anggota_id')->unsigned();
            $table->bigInteger('transaksi_harian_biaya_id')->unsigned();
            $table->integer('lama_cicilan');
            $table->double('bunga');
            $table->double('jumlah_pinjaman')->default(0);
            $table->double('angsuran_pinjaman')->default(0);
            $table->double('angsuran_bulanan')->default(0);
            $table->double('denda')->default(0);
            $table->date('periode');
            $table->boolean('status')->default(false);
            $table->timestamps();

            $table->foreign('transaksi_harian_biaya_id')->references('id')->on('transaksi_harian_biayas');
            $table->foreign('anggota_id')->references('id')->on('anggotas');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('transaksi_pinjamen');
    }
}
