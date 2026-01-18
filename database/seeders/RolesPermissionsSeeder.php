<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolesPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $now = Carbon::now();
        
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();
        
        // Define permissions
        $permissions = [
            'view_shipments', 'create_shipments', 'edit_shipments', 'delete_shipments',
            'view_bookings', 'create_bookings', 'edit_bookings', 'cancel_bookings',
            'view_customs', 'create_customs', 'edit_customs',
            'view_analytics', 'export_analytics',
            'manage_ads',
            'view_articles', 'create_articles', 'edit_articles', 'delete_articles', 'publish_articles',
            'upload_media', 'delete_media',
            'manage_users', 'manage_roles', 'manage_permissions',
        ];
        
        // Create permissions
        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }
        
        $this->command->info('✓ Created ' . count($permissions) . ' permissions');
        
        // Create roles
        $adminRole = Role::firstOrCreate(['name' => 'admin']);
        $adminRole->syncPermissions(Permission::all());
        
        $managerRole = Role::firstOrCreate(['name' => 'manager']);
        $managerRole->syncPermissions([
            'view_shipments', 'create_shipments', 'edit_shipments', 'delete_shipments',
            'view_bookings', 'create_bookings', 'edit_bookings', 'cancel_bookings',
            'view_customs', 'create_customs', 'edit_customs',
            'view_analytics', 'export_analytics',
            'view_articles',
        ]);
        
        $userRole = Role::firstOrCreate(['name' => 'user']);
        $userRole->syncPermissions([
            'view_shipments', 'create_shipments',
            'view_bookings', 'create_bookings',
            'view_articles',
        ]);
        
        $this->command->info('✓ Created 3 roles (admin, manager, user)');
        
        // Create test users
        DB::table('users')->updateOrInsert(
            ['email' => 'admin@gamarky.com'],
            [
                'name' => 'Admin User',
                'password' => Hash::make('admin123'),
                'email_verified_at' => $now,
                'created_at' => $now,
                'updated_at' => $now,
            ]
        );
        
        DB::table('users')->updateOrInsert(
            ['email' => 'manager@gamarky.com'],
            [
                'name' => 'Manager User',
                'password' => Hash::make('manager123'),
                'email_verified_at' => $now,
                'created_at' => $now,
                'updated_at' => $now,
            ]
        );
        
        DB::table('users')->updateOrInsert(
            ['email' => 'user@gamarky.com'],
            [
                'name' => 'Test User',
                'password' => Hash::make('user123'),
                'email_verified_at' => $now,
                'created_at' => $now,
                'updated_at' => $now,
            ]
        );
        
        // Assign roles
        $adminUser = \App\Models\User::where('email', 'admin@gamarky.com')->first();
        $managerUser = \App\Models\User::where('email', 'manager@gamarky.com')->first();
        $testUser = \App\Models\User::where('email', 'user@gamarky.com')->first();
        
        $adminUser->syncRoles(['admin']);
        $managerUser->syncRoles(['manager']);
        $testUser->syncRoles(['user']);
        
        $this->command->info('✓ Created 3 users:');
        $this->command->info('  - admin@gamarky.com / admin123 (admin role)');
        $this->command->info('  - manager@gamarky.com / manager123 (manager role)');
        $this->command->info('  - user@gamarky.com / user123 (user role)');
    }
}
