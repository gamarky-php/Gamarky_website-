<?php

namespace App\Livewire\Shared;

use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithPagination;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class UsersRolesMatrix extends Component
{
    use WithPagination;

    public $search = '';
    public $filterRole = '';
    public $showAssignModal = false;
    public $selectedUserId = null;
    public $selectedRoles = [];

    public function openAssignModal($userId)
    {
        $this->selectedUserId = $userId;
        
        // Get current user roles
        $this->selectedRoles = DB::table('model_has_roles')
            ->where('model_type', 'App\\Models\\User')
            ->where('model_id', $userId)
            ->pluck('role_id')
            ->toArray();
        
        $this->showAssignModal = true;
    }

    public function assignRoles()
    {
        $user = DB::table('users')->find($this->selectedUserId);
        
        if ($user) {
            // Remove all existing roles
            DB::table('model_has_roles')
                ->where('model_type', 'App\\Models\\User')
                ->where('model_id', $this->selectedUserId)
                ->delete();

            // Assign new roles
            foreach ($this->selectedRoles as $roleId) {
                DB::table('model_has_roles')->insert([
                    'role_id' => $roleId,
                    'model_type' => 'App\\Models\\User',
                    'model_id' => $this->selectedUserId,
                ]);
            }

            $this->closeModal();
            session()->flash('success', 'تم تحديث الأدوار بنجاح');
        }
    }

    public function closeModal()
    {
        $this->showAssignModal = false;
        $this->selectedUserId = null;
        $this->selectedRoles = [];
    }

    public function getUsersProperty()
    {
        $query = DB::table('users')
            ->select('users.*')
            ->orderBy('users.created_at', 'desc');

        if ($this->search) {
            $query->where(function($q) {
                $q->where('users.name', 'like', '%' . $this->search . '%')
                  ->orWhere('users.email', 'like', '%' . $this->search . '%');
            });
        }

        if ($this->filterRole) {
            $query->join('model_has_roles', function($join) {
                $join->on('users.id', '=', 'model_has_roles.model_id')
                     ->where('model_has_roles.model_type', '=', 'App\\Models\\User');
            })
            ->where('model_has_roles.role_id', $this->filterRole);
        }

        return $query->paginate(20);
    }

    public function getRolesProperty()
    {
        return Role::all();
    }

    public function getUserRoles($userId)
    {
        return DB::table('model_has_roles')
            ->join('roles', 'model_has_roles.role_id', '=', 'roles.id')
            ->where('model_has_roles.model_type', 'App\\Models\\User')
            ->where('model_has_roles.model_id', $userId)
            ->pluck('roles.name')
            ->toArray();
    }

    public function render()
    {
        return view('livewire.shared.users-roles-matrix', [
            'users' => $this->users,
            'roles' => $this->roles,
        ]);
    }
}
