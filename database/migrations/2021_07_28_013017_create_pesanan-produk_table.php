<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePesananProdukTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pesanan_produk', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_petshop');
            $table->unsignedBigInteger('id_produk');
            $table->unsignedBigInteger('id_transaksi');
            $table->unsignedBigInteger('id_customer');
            $table->integer('jumlah_pesanan');
            $table->dateTime('waktu_pemesanan', $precision= 0);
            $table->timestamps();

            $table->foreign('id_petshop')->references('id')
                ->on('users');
            $table->foreign('id_customer')->references('id')
                ->on('users');
            $table->foreign('id_produk')->references('id')
                ->on('produk');
            $table->foreign('id_transaksi')->references('id')
                ->on('transaksi');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('pesanan-produk');
    }
}
