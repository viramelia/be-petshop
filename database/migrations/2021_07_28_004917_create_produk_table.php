<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProdukTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('produk', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_petshop');
            $table->unsignedBigInteger('id_jns_produk');
            $table->string('nama', 30);
            $table->string('foto', 100);
            $table->text('deskripsi');
            $table->date('tgl_masuk');
            $table->date('expire');
            $table->integer('stok_produk');
            $table->integer('harga_satuan_produk');
            $table->timestamps();

            $table->foreign('id_petshop')->references('id')
                ->on('users');
            $table->foreign('id_jns_produk')->references('id')
                ->on('jns_produk');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('produk');
    }
}
