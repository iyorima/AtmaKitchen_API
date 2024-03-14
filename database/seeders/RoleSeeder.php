<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Role::factory()->create([
            'role' => 'Customer'
        ]);
        Role::factory()->create([
            'role' => 'Owner'
        ]);
        Role::factory()->create([
            'role' => 'Admin'
        ]);
        Role::factory()->create([
            'role' => 'Manager Operasional'
        ]);
        Role::factory()->create([
            'role' => 'Driver'
        ]);
        Role::factory()->create([
            'role' => 'Penitip'
        ]);
    }
}
