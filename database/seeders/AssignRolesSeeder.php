<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\DB;

class AssignRolesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * يقوم بإسناد الـ roles للمستخدمين الموجودين بناءً على is_admin وحقول أخرى
     */
    public function run(): void
    {
        $this->command->info('👤 Assigning roles to existing users...');

        DB::beginTransaction();
        try {
            // Reset cached roles and permissions
            app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

            $users = User::all();
            $assignedCount = 0;

            foreach ($users as $user) {
                // تخطي المستخدمين الذين لديهم roles مسبقاً (optional)
                // if ($user->roles()->count() > 0) {
                //     $this->command->line("   - User #{$user->id} already has roles, skipping");
                //     continue;
                // }

                // Clear existing roles for clean assignment
                $user->syncRoles([]);

                // Strategy 1: If is_admin = 1, assign admin role
                if ((int)$user->is_admin === 1) {
                    $user->assignRole('admin');
                    $this->command->line("   ✓ User #{$user->id} ({$user->email}) => admin");
                    $assignedCount++;
                    continue;
                }

                // Strategy 2: Check if user has provider field (importer/exporter/etc)
                if (isset($user->provider) && !empty($user->provider)) {
                    $roleName = $this->mapProviderToRole($user->provider);
                    if ($roleName && Role::where('name', $roleName)->exists()) {
                        $user->assignRole($roleName);
                        $this->command->line("   ✓ User #{$user->id} ({$user->email}) => {$roleName}");
                        $assignedCount++;
                        continue;
                    }
                }

                // Strategy 3: Check if user has role_id field
                if (isset($user->role_id) && !empty($user->role_id)) {
                    $roleName = $this->mapRoleIdToRole($user->role_id);
                    if ($roleName && Role::where('name', $roleName)->exists()) {
                        $user->assignRole($roleName);
                        $this->command->line("   ✓ User #{$user->id} ({$user->email}) => {$roleName}");
                        $assignedCount++;
                        continue;
                    }
                }

                // Strategy 4: Default to 'user' role for authenticated users
                if (Role::where('name', 'user')->exists()) {
                    $user->assignRole('user');
                    $this->command->line("   ✓ User #{$user->id} ({$user->email}) => user (default)");
                    $assignedCount++;
                }
            }

            DB::commit();
            $this->command->info("   ✅ {$assignedCount} users assigned roles successfully!");
        } catch (\Exception $e) {
            DB::rollBack();
            $this->command->error('❌ Error assigning roles: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Map provider field to role name.
     * تخصيص حسب القيم الموجودة في جدول users.provider
     */
    protected function mapProviderToRole(?string $provider): ?string
    {
        if (!$provider) {
            return null;
        }

        $mapping = [
            'importer' => 'importer',
            'import' => 'importer',
            'exporter' => 'exporter',
            'export' => 'exporter',
            'manufacturer' => 'manufacturer',
            'manufacturing' => 'manufacturer',
            'agent' => 'agent',
            'broker' => 'broker',
            'customs' => 'broker',
            'logistics' => 'logistics',
            'container' => 'logistics',
        ];

        $providerLower = strtolower($provider);
        return $mapping[$providerLower] ?? null;
    }

    /**
     * Map role_id field to role name.
     * تخصيص حسب القيم الموجودة في جدول users.role_id
     */
    protected function mapRoleIdToRole($roleId): ?string
    {
        if (!$roleId) {
            return null;
        }

        // Example mapping - adjust based on your actual role_id values
        $mapping = [
            1 => 'admin',
            2 => 'importer',
            3 => 'exporter',
            4 => 'manufacturer',
            5 => 'agent',
            6 => 'broker',
            7 => 'logistics',
            8 => 'analyst',
            9 => 'user',
        ];

        return $mapping[$roleId] ?? null;
    }
}
