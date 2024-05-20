<?php

namespace Database\Seeders;

use App\Models\Akun;
use Illuminate\Database\Seeder;

class AkunSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Akun::factory()->create([
            'email' => 'nepreufrebeije-3962@yopmail.com',
            'password' => bcrypt('admin123'),
            'id_role' => 1,
            'email_verified_at' => now()
        ]);
        Akun::factory()->create([
            'email' => 'user@gmail.com',
            'password' => bcrypt('admin123'),
            'id_role' => 1,
            'email_verified_at' => now()
        ]);
        Akun::factory()->create([
            'email' => 'admin@gmail.com',
            'password' => bcrypt('admin123'),
            'id_role' => 3,
            'email_verified_at' => now()
        ]);
        Akun::factory()->count(10)->create();
    }
}
