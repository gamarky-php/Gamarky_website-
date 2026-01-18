<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\DB;

class PermissionsSeeder extends Seeder
{
    /**
     * All dashboard abilities that must exist as permissions in the database.
     * هذه القائمة يجب أن تتطابق مع abilities في AuthServiceProvider
     */
    protected $permissions = [
        'access-dashboard',
        'manage-dashboard-settings',
        'view-import-section',
        'view-export-section',
        'view-analytics',
        'manage-users',
        'manage-roles',
        'view-customs-section',
        'view-containers-section',
        'view-agents-section',
        'view-manufacturing-section',
    ];

    /**
     * Role definitions with their assigned permissions.
     * يمكن تعديلها حسب احتياجات المشروع
     */
    protected $roles = [
        'admin' => [
            'display_name' => 'Administrator',
            'permissions' => 'all', // Special case: all permissions
        ],
        'user' => [
            'display_name' => 'User',
            'permissions' => [
                'access-dashboard',
            ],
        ],
        'importer' => [
            'display_name' => 'Importer',
            'permissions' => [
                'access-dashboard',
                'view-import-section',
            ],
        ],
        'exporter' => [
            'display_name' => 'Exporter',
            'permissions' => [
                'access-dashboard',
                'view-export-section',
            ],
        ],
        'manufacturer' => [
            'display_name' => 'Manufacturer',
            'permissions' => [
                'access-dashboard',
                'view-manufacturing-section',
            ],
        ],
        'agent' => [
            'display_name' => 'Agent',
            'permissions' => [
                'access-dashboard',
                'view-agents-section',
            ],
        ],
        'broker' => [
            'display_name' => 'Customs Broker',
            'permissions' => [
                'access-dashboard',
                'view-customs-section',
            ],
        ],
        'logistics' => [
            'display_name' => 'Logistics Manager',
            'permissions' => [
                'access-dashboard',
                'view-containers-section',
            ],
        ],
        'analyst' => [
            'display_name' => 'Analyst',
            'permissions' => [
                'access-dashboard',
                'view-analytics',
            ],
        ],
        'settings-manager' => [
            'display_name' => 'Settings Manager',
            'permissions' => [
                'access-dashboard',
                'manage-dashboard-settings',
            ],
        ],
        'user-manager' => [
            'display_name' => 'User Manager',
            'permissions' => [
                'access-dashboard',
                'manage-users',
                'manage-roles',
            ],
        ],
    ];

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        $this->command->info('🔐 Starting Permissions & Roles Seeding...');

        DB::beginTransaction();
        try {
            // Step 1: Create/Update all permissions
            $this->seedPermissions();

            // Step 2: Create/Update all roles
            $this->seedRoles();

            // Step 3: Assign permissions to roles
            $this->assignPermissionsToRoles();

            DB::commit();
            $this->command->info('✅ Permissions & Roles seeded successfully!');
        } catch (\Exception $e) {
            DB::rollBack();
            $this->command->error('❌ Error seeding permissions: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Seed all permissions into database.
     */
    protected function seedPermissions(): void
    {
        $this->command->info('📝 Creating permissions...');

        foreach ($this->permissions as $permission) {
            Permission::firstOrCreate(
                ['name' => $permission, 'guard_name' => 'web'],
                ['name' => $permission, 'guard_name' => 'web']
            );
            $this->command->line("   - {$permission}");
        }

        $this->command->info("   ✓ {count($this->permissions)} permissions created/verified");
    }

    /**
     * Seed all roles into database.
     */
    protected function seedRoles(): void
    {
        $this->command->info('👥 Creating roles...');

        foreach ($this->roles as $roleName => $roleData) {
            Role::firstOrCreate(
                ['name' => $roleName, 'guard_name' => 'web'],
                [
                    'name' => $roleName,
                    'guard_name' => 'web',
                ]
            );
            $this->command->line("   - {$roleName} ({$roleData['display_name']})");
        }

        $this->command->info("   ✓ " . count($this->roles) . " roles created/verified");
    }

    /**
     * Assign permissions to roles based on configuration.
     */
    protected function assignPermissionsToRoles(): void
    {
        $this->command->info('🔗 Assigning permissions to roles...');

        foreach ($this->roles as $roleName => $roleData) {
            $role = Role::where('name', $roleName)->where('guard_name', 'web')->first();

            if (!$role) {
                $this->command->warn("   ⚠ Role {$roleName} not found, skipping...");
                continue;
            }

            // Clear existing permissions for clean assignment
            $role->syncPermissions([]);

            if ($roleData['permissions'] === 'all') {
                // Admin gets all permissions
                $role->givePermissionTo($this->permissions);
                $this->command->line("   - {$roleName}: ALL permissions (" . count($this->permissions) . ")");
            } else {
                // Assign specific permissions
                $permissions = $roleData['permissions'];
                $role->givePermissionTo($permissions);
                $this->command->line("   - {$roleName}: " . count($permissions) . " permissions");
            }
        }

        $this->command->info('   ✓ Permissions assigned successfully');
    }

    /**
     * Get all permission names (useful for other seeders/commands).
     */
    public function getPermissions(): array
    {
        return $this->permissions;
    }

    /**
     * Get all role names (useful for other seeders/commands).
     */
    public function getRoles(): array
    {
        return array_keys($this->roles);
    }
}
