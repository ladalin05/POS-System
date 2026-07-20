<?php

namespace App\Services;

use App\Models\Admin\Menu;
use App\Models\Admin\Role;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\DB;

class RoleService
{

    public function getFormOptions(?int $id = null): array
    {
        $menus = Menu::with('children')->whereNull('parent_id')->orderBy('order')->get();

        $access = [];
        if ($id) {
            $role = Role::with('permissions')->findOrFail($id);
            foreach ($role->permissions as $permission) {
                $access[$permission->slug] = $permission->id;
                $access[$permission->menu_id] = $permission->id;
            }
        }

        return [
            'menus' => $menus,
            'access' => $access,
        ];
    }

    public function save(array $validated, FormRequest $request, ?int $id = null): Role
    {
        $permissions = json_decode($request->permissions, true) ?: [];
        $administrator = $request->administrator ? 1 : 0;

        $data = [
            'name_en' => $validated['name_en'],
            'name_kh' => $validated['name_kh'] ?? null,
            'administrator' => $administrator,
            'description' => $validated['description'] ?? null,
        ];

        $role = Role::updateOrCreate(['id' => $id], $data);
        $roleId = $role->id;

        if ($role->users->count() > 0) {
            foreach ($role->users as $user) {
                revoke_session($user->id);
            }
        }

        DB::table('role_permission')->where('role_id', $roleId)->delete();

        $permissions = array_filter(array_unique($permissions));

        if ($administrator === 0 && $permissions) {
            $rolePermission = [];
            foreach ($permissions as $permissionId) {
                if ($permissionId === 'all') {
                    continue;
                }
                $rolePermission[] = [
                    'role_id' => $roleId,
                    'permission_id' => $permissionId,
                    'created_at' => now(),
                ];
            }
            DB::table('role_permission')->insert($rolePermission);
        }

        return $role;
    }
}