<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Hash;

class AdminTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::create([
            'nama_lengkap' => 'admin',
            'role' => 'admin',
            'alamat' => 'sengkang',
            'no_hp' => '082154844596',
            'email' => 'admin@petshop.com',
            'password' => Hash::make('admin123'), 
        ]);
    }
}
