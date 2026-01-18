<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Spatie\Permission\Models\Permission;
use App\Providers\AuthServiceProvider;
use Illuminate\Support\Facades\DB;

class SyncPermissions extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:sync-permissions 
                            {--clean : Remove permissions not defined in AuthServiceProvider}
                            {--force : Force sync without confirmation}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sync permissions from AuthServiceProvider to database (Spatie Permission)';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $this->info('🔄 Starting Permission Synchronization...');
        $this->newLine();

        // Get abilities from AuthServiceProvider
        $provider = app(AuthServiceProvider::class);
        $definedAbilities = $provider->getDashboardAbilities();

        if (empty($definedAbilities)) {
            $this->error('❌ No abilities found in AuthServiceProvider.');
            return Command::FAILURE;
        }

        $this->info('📋 Found ' . count($definedAbilities) . ' abilities in AuthServiceProvider:');
        foreach ($definedAbilities as $ability) {
            $this->line("   - {$ability}");
        }
        $this->newLine();

        // Get existing permissions from database
        $existingPermissions = Permission::where('guard_name', 'web')
            ->pluck('name')
            ->toArray();

        // Calculate differences
        $toCreate = array_diff($definedAbilities, $existingPermissions);
        $toRemove = array_diff($existingPermissions, $definedAbilities);
        $existing = array_intersect($definedAbilities, $existingPermissions);

        // Show summary
        $this->info('📊 Synchronization Summary:');
        $this->line("   ✓ Already exists: " . count($existing));
        $this->line("   + To create: " . count($toCreate));
        if ($this->option('clean')) {
            $this->line("   - To remove: " . count($toRemove));
        }
        $this->newLine();

        // Confirm if not forced
        if (!$this->option('force')) {
            if (!$this->confirm('Do you want to proceed with synchronization?', true)) {
                $this->warn('⚠ Synchronization cancelled.');
                return Command::SUCCESS;
            }
        }

        DB::beginTransaction();
        try {
            // Create missing permissions
            if (!empty($toCreate)) {
                $this->info('➕ Creating new permissions:');
                foreach ($toCreate as $ability) {
                    Permission::create([
                        'name' => $ability,
                        'guard_name' => 'web',
                    ]);
                    $this->line("   ✓ Created: {$ability}");
                }
                $this->newLine();
            } else {
                $this->info('✓ No new permissions to create.');
                $this->newLine();
            }

            // Remove obsolete permissions if --clean flag is set
            if ($this->option('clean') && !empty($toRemove)) {
                $this->warn('🗑️  Removing obsolete permissions:');
                foreach ($toRemove as $permission) {
                    $permissionModel = Permission::where('name', $permission)
                        ->where('guard_name', 'web')
                        ->first();
                    
                    if ($permissionModel) {
                        // Remove from all roles first
                        $permissionModel->roles()->detach();
                        // Remove from all users
                        $permissionModel->users()->detach();
                        // Delete permission
                        $permissionModel->delete();
                        $this->line("   ✓ Removed: {$permission}");
                    }
                }
                $this->newLine();
            }

            // Reset cached permissions
            app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

            DB::commit();
            $this->info('✅ Permissions synchronized successfully!');
            return Command::SUCCESS;

        } catch (\Exception $e) {
            DB::rollBack();
            $this->error('❌ Error during synchronization: ' . $e->getMessage());
            return Command::FAILURE;
        }
    }
}
