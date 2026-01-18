<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

/**
 * Users & Roles Matrix API Controller
 * 
 * Features:
 * - User management (CRUD)
 * - Roles management
 * - Permissions assignment
 * - Bulk permission updates
 * - Role-Permission matrix
 */
class UsersRolesController extends Controller
{
    /**
     * Get all users with roles
     * 
     * GET /api/users
     */
    public function getUsers(Request $request)
    {
        $query = DB::table('users')
            ->select([
                'users.*',
                DB::raw('GROUP_CONCAT(DISTINCT roles.name) as roles')
            ])
            ->leftJoin('user_roles', 'users.id', '=', 'user_roles.user_id')
            ->leftJoin('roles', 'user_roles.role_id', '=', 'roles.id')
            ->groupBy('users.id');
        
        if ($request->has('search')) {
            $query->where(function($q) use ($request) {
                $q->where('users.name', 'like', '%' . $request->search . '%')
                  ->orWhere('users.email', 'like', '%' . $request->search . '%');
            });
        }
        
        if ($request->has('role_id')) {
            $query->having('roles', 'like', '%' . DB::table('roles')->where('id', $request->role_id)->value('name') . '%');
        }
        
        $users = $query->paginate(20);
        
        return response()->json([
            'success' => true,
            'data' => $users->items(),
            'pagination' => [
                'total' => $users->total(),
                'per_page' => $users->perPage(),
                'current_page' => $users->currentPage(),
                'last_page' => $users->lastPage(),
            ]
        ]);
    }

    /**
     * Create user
     * 
     * POST /api/users
     */
    public function createUser(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8',
            'role_ids' => 'nullable|array',
            'role_ids.*' => 'integer|exists:roles,id',
        ]);
        
        $userId = DB::table('users')->insertGetId([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);
        
        // Assign roles
        if (isset($validated['role_ids']) && count($validated['role_ids']) > 0) {
            $roleData = [];
            foreach ($validated['role_ids'] as $roleId) {
                $roleData[] = [
                    'user_id' => $userId,
                    'role_id' => $roleId,
                    'created_at' => Carbon::now(),
                ];
            }
            DB::table('user_roles')->insert($roleData);
        }
        
        return response()->json([
            'success' => true,
            'message' => 'User created successfully',
            'data' => ['id' => $userId]
        ], 201);
    }

    /**
     * Update user roles
     * 
     * PUT /api/users/{id}/roles
     */
    public function updateUserRoles(Request $request, int $id)
    {
        $validated = $request->validate([
            'role_ids' => 'required|array',
            'role_ids.*' => 'integer|exists:roles,id',
        ]);
        
        // Remove existing roles
        DB::table('user_roles')->where('user_id', $id)->delete();
        
        // Assign new roles
        $roleData = [];
        foreach ($validated['role_ids'] as $roleId) {
            $roleData[] = [
                'user_id' => $id,
                'role_id' => $roleId,
                'created_at' => Carbon::now(),
            ];
        }
        DB::table('user_roles')->insert($roleData);
        
        return response()->json([
            'success' => true,
            'message' => 'User roles updated successfully'
        ]);
    }

    /**
     * Get all roles with permissions
     * 
     * GET /api/roles
     */
    public function getRoles()
    {
        $roles = DB::table('roles')
            ->select('roles.*')
            ->orderBy('name')
            ->get();
        
        // Get permissions for each role
        foreach ($roles as $role) {
            $role->permissions = DB::table('role_permissions')
                ->join('permissions', 'role_permissions.permission_id', '=', 'permissions.id')
                ->where('role_permissions.role_id', $role->id)
                ->select('permissions.*')
                ->get();
        }
        
        return response()->json([
            'success' => true,
            'data' => $roles
        ]);
    }

    /**
     * Create role
     * 
     * POST /api/roles
     */
    public function createRole(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:roles,name',
            'display_name' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'permission_ids' => 'nullable|array',
            'permission_ids.*' => 'integer|exists:permissions,id',
        ]);
        
        $roleId = DB::table('roles')->insertGetId([
            'name' => $validated['name'],
            'display_name' => $validated['display_name'] ?? $validated['name'],
            'description' => $validated['description'] ?? null,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);
        
        // Assign permissions
        if (isset($validated['permission_ids']) && count($validated['permission_ids']) > 0) {
            $permData = [];
            foreach ($validated['permission_ids'] as $permId) {
                $permData[] = [
                    'role_id' => $roleId,
                    'permission_id' => $permId,
                    'created_at' => Carbon::now(),
                ];
            }
            DB::table('role_permissions')->insert($permData);
        }
        
        return response()->json([
            'success' => true,
            'message' => 'Role created successfully',
            'data' => ['id' => $roleId]
        ], 201);
    }

    /**
     * Update role permissions
     * 
     * PUT /api/roles/{id}/permissions
     */
    public function updateRolePermissions(Request $request, int $id)
    {
        $validated = $request->validate([
            'permission_ids' => 'required|array',
            'permission_ids.*' => 'integer|exists:permissions,id',
        ]);
        
        // Remove existing permissions
        DB::table('role_permissions')->where('role_id', $id)->delete();
        
        // Assign new permissions
        $permData = [];
        foreach ($validated['permission_ids'] as $permId) {
            $permData[] = [
                'role_id' => $id,
                'permission_id' => $permId,
                'created_at' => Carbon::now(),
            ];
        }
        DB::table('role_permissions')->insert($permData);
        
        return response()->json([
            'success' => true,
            'message' => 'Role permissions updated successfully'
        ]);
    }

    /**
     * Get all permissions
     * 
     * GET /api/permissions
     */
    public function getPermissions()
    {
        $permissions = DB::table('permissions')
            ->orderBy('group')
            ->orderBy('name')
            ->get();
        
        // Group by category
        $grouped = $permissions->groupBy('group');
        
        return response()->json([
            'success' => true,
            'data' => $permissions,
            'grouped' => $grouped
        ]);
    }

    /**
     * Get role-permission matrix
     * 
     * GET /api/roles/matrix
     */
    public function getMatrix()
    {
        $roles = DB::table('roles')->orderBy('name')->get();
        $permissions = DB::table('permissions')->orderBy('group')->orderBy('name')->get();
        
        $matrix = [];
        foreach ($permissions as $permission) {
            $row = [
                'permission_id' => $permission->id,
                'permission_name' => $permission->name,
                'permission_group' => $permission->group,
                'roles' => []
            ];
            
            foreach ($roles as $role) {
                $hasPermission = DB::table('role_permissions')
                    ->where('role_id', $role->id)
                    ->where('permission_id', $permission->id)
                    ->exists();
                
                $row['roles'][$role->id] = $hasPermission;
            }
            
            $matrix[] = $row;
        }
        
        return response()->json([
            'success' => true,
            'roles' => $roles,
            'matrix' => $matrix
        ]);
    }

    /**
     * Bulk update matrix
     * 
     * POST /api/roles/matrix/update
     */
    public function updateMatrix(Request $request)
    {
        $validated = $request->validate([
            'updates' => 'required|array',
            'updates.*.role_id' => 'required|integer|exists:roles,id',
            'updates.*.permission_id' => 'required|integer|exists:permissions,id',
            'updates.*.enabled' => 'required|boolean',
        ]);
        
        foreach ($validated['updates'] as $update) {
            $exists = DB::table('role_permissions')
                ->where('role_id', $update['role_id'])
                ->where('permission_id', $update['permission_id'])
                ->exists();
            
            if ($update['enabled'] && !$exists) {
                // Add permission
                DB::table('role_permissions')->insert([
                    'role_id' => $update['role_id'],
                    'permission_id' => $update['permission_id'],
                    'created_at' => Carbon::now(),
                ]);
            } elseif (!$update['enabled'] && $exists) {
                // Remove permission
                DB::table('role_permissions')
                    ->where('role_id', $update['role_id'])
                    ->where('permission_id', $update['permission_id'])
                    ->delete();
            }
        }
        
        return response()->json([
            'success' => true,
            'message' => 'Matrix updated successfully'
        ]);
    }
}
