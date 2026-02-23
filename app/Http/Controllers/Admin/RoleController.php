<?php

namespace App\Http\Controllers\Admin;

use App\Models\Admin\Menu;
use App\Models\Admin\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\DataTables\Admin\RoleDataTable;
use App\Models\Admin\Permission;

class RoleController extends Controller
{
    public function index(RoleDataTable $dataTable)
    {
        return $dataTable->render('admin.roles.index');
    }
    public function add()
    {
        $form = new Role();
        $menus = Menu::with('children')->whereNull('parent_id')->orderBy('order')->get();
        $access = [];
        $title = __('global.add_new');
        return view('admin.roles.form', compact('form', 'access', 'menus', 'title'));
    }
    public function edit($id)
    {
        $form = Role::find($id);
        $menus = Menu::with('children')->whereNull('parent_id')->orderBy('order')->get();
        $permissions = $form->permissions;
        $access = [];
        foreach ($permissions as $permission) {
            $access[$permission->slug] = $permission->id;
            $access[$permission->menu_id] = $permission->id;
        }
        return view('admin.roles.form', compact('form', 'access', 'menus'));
    }
    public function save(Request $request, $id = null)
    {
        $request->validate([
            'name_en' => 'required'
        ]);
        $permissions = json_decode($request->permissions, true);
        $administrator = $request->administrator ? 1 : 0;
        $rolePermission = [];
        $data = [
            'name_en' => $request->name_en,
            'name_kh' => $request->name_kh,
            'administrator' => $administrator,
            'description' => $request->description
        ];
        $role = Role::updateOrCreate(['id' => $id], $data);
        $id = $role->id;
        if ($role->users->count() > 0) {
            foreach ($role->users as $user) {
                revoke_session($user->id);
            }
        }
        DB::table('role_permission')->where('role_id', $id)->delete();
        $permissions = array_filter(array_unique($permissions));
        if ($administrator == 0 && $permissions) {
            foreach ($permissions as $permission_id) {
                if($permission_id == 'all') {
                    continue;
                }
                $rolePermission[] = [
                    'role_id' => $id,
                    'permission_id' => $permission_id,
                    'created_at' => now(),
                ];
            }
            DB::table('role_permission')->insert($rolePermission);
        }
        return json([
            'status' => 'success',
            'message' => !empty($id) ? __('messages.role_updated') : __('messages.role_saved'),
            'redirect' => route('users-management.roles.index')
        ]);
    }
    public function permission($id)
    {
        return view('admin.roles.permission');
    }
    public function detele($id)
    {
        try {
            if($id == 1) {
                return json([
                    'status' => 'error',
                    'message' => __('messages.role_cannot_delete')
                ]);
            }
            $role = Role::find($id);
            $role->delete();
            return json([
                'status' => 'success',
                'message' => __('messages.role_deleted')
            ]);
        } catch (\Exception $e) {
            return json([
                'status' => 'error',
                'message' => $e->getMessage()
            ]);
        }
    }
}
