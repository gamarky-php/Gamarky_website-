<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $email = env('ADMIN_EMAIL', 'admin@example.com');
        $password = env('ADMIN_PASSWORD', 'StrongPass!123');

        User::updateOrCreate(
            ['email' => $email],
            [
                'name' => env('ADMIN_NAME', 'Site Admin'),
                'password' => bcrypt($password),
                'is_admin' => true,
                'email_verified_at' => now(),
            ]
        );

        $this->command->info("Admin user ensured: {$email}");
    }
}
