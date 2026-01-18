<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeder.
     */
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Define all permissions
        $permissions = [
            // Containers
            'containers.view', 'containers.quote', 'containers.book', 'containers.manage',
            'tracking.read', 'tracking.update',
            
            // Brokers
            'brokers.view', 'brokers.create', 'brokers.edit', 'brokers.delete',
            'brokers.search', 'brokers.review', 'brokers.approve',
            
            // Clearance
            'clearance.view', 'clearance.create', 'clearance.manage', 'clearance.approve',
            
            // Costs
            'costs.calculate', 'costs.save', 'costs.view', 'costs.approve',
            
            // Trucks
            'trucks.view', 'trucks.quote', 'trucks.book', 'trucks.manage',
            
            // Ads
            'ads.view', 'ads.create', 'ads.edit', 'ads.delete', 'ads.publish',
            
            // Articles
            'articles.view', 'articles.create', 'articles.edit', 'articles.delete', 'articles.publish',
            
            // Media
            'media.view', 'media.upload', 'media.delete', 'media.manage',
            
            // Notifications
            'notifications.view', 'notifications.send', 'notifications.manage',
            
            // Subscriptions
            'subscriptions.view', 'subscriptions.manage',
            
            // Users & Roles
            'users.view', 'users.create', 'users.edit', 'users.delete', 'users.manage',
            'roles.view', 'roles.create', 'roles.edit', 'roles.delete', 'roles.assign',
            
            // Dashboard
            'dashboard.access', 'dashboard.analytics', 'dashboard.reports',
        ];

        // Create permissions
        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission, 'guard_name' => 'web']);
        }

        // Define roles with their permissions
        $roles = [
            'admin' => [
                'containers.view', 'containers.quote', 'containers.book', 'containers.manage',
                'tracking.read', 'tracking.update',
                'brokers.view', 'brokers.create', 'brokers.edit', 'brokers.delete',
                'brokers.search', 'brokers.review', 'brokers.approve',
                'clearance.view', 'clearance.create', 'clearance.manage', 'clearance.approve',
                'costs.calculate', 'costs.save', 'costs.view', 'costs.approve',
                'trucks.view', 'trucks.quote', 'trucks.book', 'trucks.manage',
                'ads.view', 'ads.create', 'ads.edit', 'ads.delete', 'ads.publish',
                'articles.view', 'articles.create', 'articles.edit', 'articles.delete', 'articles.publish',
                'media.view', 'media.upload', 'media.delete', 'media.manage',
                'notifications.view', 'notifications.send', 'notifications.manage',
                'subscriptions.view', 'subscriptions.manage',
                'users.view', 'users.create', 'users.edit', 'users.delete', 'users.manage',
                'roles.view', 'roles.create', 'roles.edit', 'roles.delete', 'roles.assign',
                'dashboard.access', 'dashboard.analytics', 'dashboard.reports',
            ],
            
            'manager' => [
                'containers.view', 'containers.quote', 'containers.book', 'containers.manage',
                'tracking.read',
                'brokers.view', 'brokers.search', 'brokers.review',
                'clearance.view', 'clearance.create', 'clearance.manage',
                'costs.calculate', 'costs.save', 'costs.view',
                'trucks.view', 'trucks.quote', 'trucks.book',
                'ads.view', 'ads.create', 'ads.edit',
                'articles.view', 'articles.create', 'articles.edit',
                'media.view', 'media.upload',
                'notifications.view', 'notifications.send',
                'users.view', 'users.create', 'users.edit',
                'dashboard.access', 'dashboard.analytics',
            ],
            
            'broker' => [
                'brokers.view', 'brokers.search',
                'clearance.view', 'clearance.create', 'clearance.manage',
                'tracking.read',
                'costs.calculate', 'costs.view',
                'notifications.view',
                'dashboard.access',
            ],
            
            'agent' => [
                'brokers.view', 'brokers.search',
                'clearance.view',
                'costs.calculate', 'costs.save', 'costs.view',
                'notifications.view',
                'dashboard.access',
            ],
            
            'importer' => [
                'containers.view', 'containers.quote', 'containers.book',
                'tracking.read',
                'brokers.view', 'brokers.search', 'brokers.review',
                'clearance.view', 'clearance.create',
                'costs.calculate', 'costs.save', 'costs.view',
                'trucks.view', 'trucks.quote', 'trucks.book',
                'notifications.view',
                'dashboard.access',
            ],
            
            'exporter' => [
                'containers.view', 'containers.quote', 'containers.book',
                'tracking.read',
                'brokers.view', 'brokers.search',
                'clearance.view',
                'costs.calculate', 'costs.save', 'costs.view',
                'trucks.view', 'trucks.quote', 'trucks.book',
                'notifications.view',
                'dashboard.access',
            ],
            
            'viewer' => [
                'containers.view',
                'tracking.read',
                'brokers.view', 'brokers.search',
                'clearance.view',
                'costs.view',
                'trucks.view',
                'ads.view',
                'articles.view',
                'notifications.view',
                'dashboard.access',
            ],
        ];

        // Create roles and assign permissions
        foreach ($roles as $roleName => $rolePermissions) {
            $role = Role::firstOrCreate(['name' => $roleName, 'guard_name' => 'web']);
            $role->syncPermissions($rolePermissions);
        }

        $this->command->info('✅ Roles and permissions created successfully!');
        $this->command->info('   - Admin: Full access (' . count($roles['admin']) . ' permissions)');
        $this->command->info('   - Manager: Operations management (' . count($roles['manager']) . ' permissions)');
        $this->command->info('   - Broker: Clearance jobs (' . count($roles['broker']) . ' permissions)');
        $this->command->info('   - Agent: Agency operations (' . count($roles['agent']) . ' permissions)');
        $this->command->info('   - Importer: Import operations (' . count($roles['importer']) . ' permissions)');
        $this->command->info('   - Exporter: Export operations (' . count($roles['exporter']) . ' permissions)');
        $this->command->info('   - Viewer: Read-only access (' . count($roles['viewer']) . ' permissions)');
    }
}
