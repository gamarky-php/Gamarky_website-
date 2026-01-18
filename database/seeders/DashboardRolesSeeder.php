<?php

namespace Database\Seeders;

/**
 * Dashboard Roles & Permissions Seeder
 * 
 * Purpose: إنشاء الأدوار والصلاحيات للوحة التحكم
 * Dependencies: spatie/laravel-permission
 * 
 * Roles Structure:
 * - Super Admin: كامل الصلاحيات
 * - Manager: إدارة الأقسام
 * - Section Managers: مدراء أقسام محددة
 * - Users: مستخدمون عاديون
 */

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class DashboardRolesSeeder extends Seeder
{
    public function run(): void
    {
        // إعادة تعيين الصلاحيات المخزنة مؤقتًا
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // ========== إنشاء Permissions ==========
        
        // Import Permissions
        Permission::create(['name' => 'view-import-operations']);
        Permission::create(['name' => 'create-import-operations']);
        Permission::create(['name' => 'edit-import-operations']);
        Permission::create(['name' => 'delete-import-operations']);
        Permission::create(['name' => 'approve-import-operations']);
        Permission::create(['name' => 'export-import-data']);

        // Export Permissions
        Permission::create(['name' => 'view-export-operations']);
        Permission::create(['name' => 'create-export-operations']);
        Permission::create(['name' => 'edit-export-operations']);
        Permission::create(['name' => 'delete-export-operations']);
        Permission::create(['name' => 'export-export-data']);

        // Manufacturing Permissions
        Permission::create(['name' => 'view-manufacturing']);
        Permission::create(['name' => 'create-manufacturing']);
        Permission::create(['name' => 'edit-manufacturing']);
        Permission::create(['name' => 'delete-manufacturing']);
        Permission::create(['name' => 'calculate-costs']);

        // Customs Permissions
        Permission::create(['name' => 'view-customs']);
        Permission::create(['name' => 'create-customs']);
        Permission::create(['name' => 'edit-customs']);
        Permission::create(['name' => 'approve-customs']);
        Permission::create(['name' => 'reject-customs']);
        Permission::create(['name' => 'assign-broker']);

        // Container Permissions
        Permission::create(['name' => 'view-containers']);
        Permission::create(['name' => 'create-containers']);
        Permission::create(['name' => 'edit-containers']);
        Permission::create(['name' => 'track-containers']);
        Permission::create(['name' => 'update-tracking']);

        // Agent Permissions
        Permission::create(['name' => 'view-agents']);
        Permission::create(['name' => 'manage-agents']);
        Permission::create(['name' => 'approve-agent-operations']);

        // Dashboard & Analytics
        Permission::create(['name' => 'view-dashboard']);
        Permission::create(['name' => 'view-kpis']);
        Permission::create(['name' => 'view-analytics']);
        Permission::create(['name' => 'export-reports']);
        Permission::create(['name' => 'create-custom-dashboards']);

        // Webhooks & Integrations
        Permission::create(['name' => 'manage-webhooks']);
        Permission::create(['name' => 'view-webhook-logs']);

        // Settings
        Permission::create(['name' => 'manage-dashboard-settings']);
        Permission::create(['name' => 'manage-users']);
        Permission::create(['name' => 'manage-roles']);

        // ========== إنشاء Roles ==========

        // Super Admin - كل الصلاحيات
        $admin = Role::create(['name' => 'admin']);
        $admin->givePermissionTo(Permission::all());

        // General Manager - معظم الصلاحيات
        $manager = Role::create(['name' => 'manager']);
        $manager->givePermissionTo([
            'view-dashboard',
            'view-kpis',
            'view-analytics',
            'export-reports',
            'view-import-operations',
            'view-export-operations',
            'view-manufacturing',
            'view-customs',
            'view-containers',
            'view-agents',
        ]);

        // Import Manager
        $importManager = Role::create(['name' => 'import_manager']);
        $importManager->givePermissionTo([
            'view-dashboard',
            'view-import-operations',
            'create-import-operations',
            'edit-import-operations',
            'approve-import-operations',
            'export-import-data',
        ]);

        // Export Manager
        $exportManager = Role::create(['name' => 'export_manager']);
        $exportManager->givePermissionTo([
            'view-dashboard',
            'view-export-operations',
            'create-export-operations',
            'edit-export-operations',
            'export-export-data',
        ]);

        // Manufacturing Manager
        $mfgManager = Role::create(['name' => 'manufacturing_manager']);
        $mfgManager->givePermissionTo([
            'view-dashboard',
            'view-manufacturing',
            'create-manufacturing',
            'edit-manufacturing',
            'calculate-costs',
        ]);

        // Customs Manager
        $customsManager = Role::create(['name' => 'customs_manager']);
        $customsManager->givePermissionTo([
            'view-dashboard',
            'view-customs',
            'create-customs',
            'edit-customs',
            'approve-customs',
            'reject-customs',
            'assign-broker',
        ]);

        // Customs Broker
        $customsBroker = Role::create(['name' => 'customs_broker']);
        $customsBroker->givePermissionTo([
            'view-customs',
            'edit-customs',
            'approve-customs',
        ]);

        // Shipping Manager
        $shippingManager = Role::create(['name' => 'shipping_manager']);
        $shippingManager->givePermissionTo([
            'view-dashboard',
            'view-containers',
            'create-containers',
            'edit-containers',
            'track-containers',
            'update-tracking',
        ]);

        // Logistics User
        $logisticsUser = Role::create(['name' => 'logistics_user']);
        $logisticsUser->givePermissionTo([
            'view-containers',
            'track-containers',
            'update-tracking',
        ]);

        // Agent Manager
        $agentManager = Role::create(['name' => 'agent_manager']);
        $agentManager->givePermissionTo([
            'view-dashboard',
            'view-agents',
            'manage-agents',
            'approve-agent-operations',
        ]);

        // Production User
        $productionUser = Role::create(['name' => 'production_user']);
        $productionUser->givePermissionTo([
            'view-manufacturing',
            'create-manufacturing',
            'calculate-costs',
        ]);

        // Import User (عادي)
        $importUser = Role::create(['name' => 'import_user']);
        $importUser->givePermissionTo([
            'view-import-operations',
            'create-import-operations',
        ]);

        // Export User (عادي)
        $exportUser = Role::create(['name' => 'export_user']);
        $exportUser->givePermissionTo([
            'view-export-operations',
            'create-export-operations',
        ]);

        // Dashboard User - وصول للوحة التحكم فقط
        $dashboardUser = Role::create(['name' => 'dashboard_user']);
        $dashboardUser->givePermissionTo(['view-dashboard']);

        // Developer - للـ Webhooks والتكامل
        $developer = Role::create(['name' => 'developer']);
        $developer->givePermissionTo([
            'manage-webhooks',
            'view-webhook-logs',
        ]);

        $this->command->info('✅ تم إنشاء ' . Role::count() . ' أدوار و ' . Permission::count() . ' صلاحية بنجاح!');
    }
}
