<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBookingLayananTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('booking_layanan', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_petshop');
            $table->unsignedBigInteger('id_customer');
            $table->unsignedBigInteger('id_layanan');
            $table->date('tgl_booking');
            $table->time('jam_mulai');
            $table->time('jam_selesai');
            $table->enum('jenis_transaksi', ['online', 'offline']);
            $table->enum('status', ['terbooking', 'selesai']);
            $table->integer('biaya')->nullable()->change(NULL);
            // not needed
            // $table->string('bukti_tf', 100)->nullable()->change(NULL);
            $table->string('jenis_hewan', 15)->nullable()->change(NULL);
            $table->integer('berat_hewan')->nullable()->change(NULL);
            $table->timestamps();

            $table->foreign('id_petshop')->references('id')
                ->on('users');
            $table->foreign('id_customer')->references('id')
                ->on('users');
            $table->foreign('id_layanan')->references('id')
                ->on('layanan');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('booking-layanan');
    }
}
