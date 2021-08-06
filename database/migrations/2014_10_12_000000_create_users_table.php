<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('email')->unique();
            $table->string('password')->nullable()->default(NULL);;
            $table->enum('role', ['admin', 'petshop', 'customer']);
            $table->string('nama_lengkap');
            $table->string('alamat', 30);
            $table->string('no_hp', 12);
            $table->string('foto', 255)->nullable()->default(NULL);
            // CUSTOMER
            $table->date('tgl_lahir')->nullable()->default(NULL);
            $table->enum('gender', ['laki-laki', 'perempuan'])->nullable()->default(NULL);
            // PETSHOP
            $table->time('jam_buka')->nullable()->default(NULL);
            $table->time('jam_tutup')->nullable()->default(NULL);
            $table->enum('nama_bank', ['bri', 'bca', 'bni', 'mandiri', 'sulselbar'])->nullable()->default(NULL);
            $table->string('no_rek', 60)->nullable()->default(NULL);
            $table->enum('status', ['non', 'aktif'])->nullable()->default(NULL);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
