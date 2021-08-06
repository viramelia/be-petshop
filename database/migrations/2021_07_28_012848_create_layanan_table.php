<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLayananTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('layanan', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_petshop');
            $table->string('nama', 30);
            $table->enum('kategori', ['kesehatan', 'kebersihan']);
            $table->string('gambar', 100);
            $table->text('deskripsi');
            $table->string('jenis_hewan', 15);
            $table->integer('biaya_layanan');
            $table->timestamps();

            $table->foreign('id_petshop')->references('id')
                ->on('users')->onDelete('cascade')
                ->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('layanan');
    }
}
