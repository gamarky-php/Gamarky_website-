<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // STEP 1: Seed Permissions & Roles FIRST (before users)
        $this->call([
            PermissionsSeeder::class,
        ]);

        // STEP 2: Default test user
        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);

        // STEP 3: Seed data for countries, ports, shipping types
        $this->call([
            CountrySeeder::class,
            ShippingTypeSeeder::class,
            PortSeeder::class,
            AdsSeeder::class,
        ]);

        // STEP 4: Assign roles to existing users (run this AFTER users exist)
        $this->call([
            AssignRolesSeeder::class,
        ]);
    }
}
