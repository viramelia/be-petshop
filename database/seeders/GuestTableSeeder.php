<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User; 

class GuestTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::create([
            'nama_lengkap' => 'guest',
            'role' => 'customer',
            'email' => 'guest@gmail.com',
            'alamat' => 'none',
            'no_hp' => '01234567890',
        ]);
    }
}
