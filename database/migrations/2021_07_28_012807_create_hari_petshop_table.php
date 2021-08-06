<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHariPetshopTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('hari_petshop', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_petshop');
            $table->unsignedBigInteger('id_hari');
            $table->timestamps();

            $table->foreign('id_petshop')->references('id')
                ->on('users');
            $table->foreign('id_hari')->references('id')
                ->on('hari');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('hari-petshop');
    }
}
