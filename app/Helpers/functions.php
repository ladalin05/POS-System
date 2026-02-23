<?php

use Illuminate\Support\Facades\DB;

use App\Models\Admin\Menu;
use App\Models\Admin\Permission;
use Illuminate\Auth\Events\Login;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Session;

if (!function_exists('setUserMenu')) {
    function setUserMenu()
    {
        $user = Auth::user();
        
        if (!$user) {
            return null;
        }

        $data = [ 'access' => [], 'menus' => [], 'permissions' => [], 'has_menus' => [], 'administrator' => false, ];
        $administrator = $user->roles->where('administrator', 1)->count() > 0;

        if ($administrator) {
            $data['administrator'] = true;
            $data['menus'] = Menu::with('children') ->whereNull('parent_id')->orderBy('order')->get();
            return $data;
        }

        $access   = [];
        $menu_ids = [];

        foreach ($user->roles as $role) {
            foreach ($role->permissions as $permission) {
                $access[md5(trim($permission->slug))] = $permission->id;
                if ($permission->is_menu == 1) {
                    $menu_ids[] = $permission->menu_id;
                }
            }
        }

        $permissions = Permission::all()->pluck('id', 'slug')->mapWithKeys(fn ($id, $slug) => [md5(trim($slug)) => $id])->toArray();
        $menu_ids = array_unique($menu_ids);
        $third  = Menu::whereIn('id', $menu_ids)->pluck('parent_id')->toArray();
        $menu_ids = array_merge($menu_ids, $third);
        $second = Menu::whereIn('id', $menu_ids)->pluck('parent_id')->toArray();
        $menu_ids = array_merge($menu_ids, $second);
        $has_menus = array_filter(array_unique($menu_ids));

        $data['access']      = $access;
        $data['permissions'] = $permissions;
        $data['has_menus']   = $has_menus;
        $data['menus']       = Menu::with('children') ->whereNull('parent_id')->orderBy('order')->get();

        return $data;
    }
}


if (!function_exists('uploadImage')) {
    function uploadImage($file, $oldImagePath = null, $folder = 'images')
    {
        if ($file) {
            if ($oldImagePath && Storage::disk('public')->exists($oldImagePath)) {
                Storage::disk('public')->delete($oldImagePath);
            }

            $filename = $file->getClientOriginalName();
            $path = $file->storeAs($folder, $filename, 'public');

            return $path;
        }

        return null;
    }
}