<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTransaksiTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transaksi', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_petshop');
            $table->unsignedBigInteger('id_customer');
            $table->unsignedBigInteger('id_admin');
            $table->enum('jenis_transaksi', ['online', 'offline']);
            $table->dateTime('waktu_pengiriman', $precision = 0)->nullable()->change();
            $table->string('bukti_tf')->nullable()->change();
            $table->dateTime('tgl_tf', $precision=0)->nullable()->change();
            $table->integer('total_harga');
            $table->enum('status', ['belum', 'lunas', 'pengiriman', 'diterima']);
            $table->timestamps();

            $table->foreign('id_petshop')->references('id')
                ->on('users');
            $table->foreign('id_customer')->references('id')
                ->on('users');
            $table->foreign('id_admin')->references('id')
                ->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('transaksi');
    }
}
