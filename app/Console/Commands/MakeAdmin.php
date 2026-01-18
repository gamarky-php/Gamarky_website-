<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;

class MakeAdmin extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:admin {email} {--name=} {--password=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Promote a user to admin or create a new admin user';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $email = $this->argument('email');
        $name = $this->option('name') ?: 'Site Admin';
        $password = $this->option('password') ?: 'StrongPass!123';

        // Check if user exists
        $user = User::where('email', $email)->first();

        if ($user) {
            // Update existing user
            $user->is_admin = true;
            
            if ($this->option('password')) {
                $user->password = Hash::make($password);
                $this->info("Updated existing user '{$user->name}' and reset password.");
            } else {
                $this->info("Promoted existing user '{$user->name}' to admin.");
            }
            
            $user->save();
        } else {
            // Create new admin user
            $user = User::create([
                'name' => $name,
                'email' => $email,
                'password' => Hash::make($password),
                'email_verified_at' => now(),
                'is_admin' => true,
            ]);
            
            $this->info("Created new admin user '{$name}' with email: {$email}");
        }

        $this->line("Admin user ready! Login with email: {$email}");
        
        return Command::SUCCESS;
    }
}
