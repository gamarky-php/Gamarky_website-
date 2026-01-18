<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;

class MakeUserAdmin extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'user:make-admin {email : The email of the user to make admin}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Make a user admin by email address';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $email = $this->argument('email');

        $user = User::where('email', $email)->first();

        if (!$user) {
            $this->error("User with email '{$email}' not found.");
            return 1;
        }

        // Try using Spatie Permission first
        if (method_exists($user, 'assignRole')) {
            // Create admin role if it doesn't exist
            $role = \Spatie\Permission\Models\Role::firstOrCreate(['name' => 'admin']);

            if ($user->hasRole('admin')) {
                $this->info("User '{$user->name}' ({$email}) is already an admin.");
                return 0;
            }

            $user->assignRole('admin');
            $this->info("Successfully assigned admin role to user '{$user->name}' ({$email}).");
        } else {
            // Fallback to is_admin field
            if (!$user->is_admin) {
                $user->update(['is_admin' => true]);
                $this->info("Successfully set is_admin=true for user '{$user->name}' ({$email}).");
            } else {
                $this->info("User '{$user->name}' ({$email}) is already an admin.");
            }
        }

        return 0;
    }
}
