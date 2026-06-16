<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();
        Role::create([
            'name'=> 'super_admin',
        ]);
        User::factory()->create([
            'name' => 'Super Admin',
            'email' => 'sadmin@admin.com',
            'role_id' => 1,
            'password' => Hash::make('suad123'),
        ]);

        
    }
}
