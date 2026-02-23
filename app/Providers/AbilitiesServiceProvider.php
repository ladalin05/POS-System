<?php

namespace App\Providers;

use App\Models\Admin\Menu;
use App\Models\Admin\Permission;
use Illuminate\Auth\Events\Login;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\ServiceProvider;

class AbilitiesServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        Event::listen(Login::class, function ($app) {
            $user = $app->user;
            if ($user) {
                $administrator = $user->roles->where('administrator', 1)->count() > 0 ? true : false;
                if ($administrator) {
                    Session::put('access', []);
                    Session::put('menus', Menu::with('children')->whereNull('parent_id')->orderBy('order')->get());
                    Session::put('permissions', []);
                    Session::put('administrator', $administrator);
                } else {
                    $permission_ids = [];
                    $permissions = [];
                    $access = [];
                    $menu_ids = [];
                    foreach ($user->roles as $role) {
                        foreach ($role->permissions as $permission) {
                            $permission_ids[] = $permission->id;
                            $access[md5(trim($permission->slug))] = $permission->id;
                            if($permission->is_menu == 1) {
                                $menu_ids[] = $permission->menu_id;
                            }
                        }
                    }
                    $permissions = Permission::all()->pluck('id', 'slug')->mapWithKeys(function ($id, $slug) {
                        return [md5(trim($slug)) => $id];
                    })->toArray();
                    $menu_ids = array_unique($menu_ids);
                    $third = Menu::whereIn('id', $menu_ids)->pluck('parent_id')->toArray();
                    $menu_ids = array_merge($menu_ids, $third);
                    $second = Menu::whereIn('id', $menu_ids)->pluck('parent_id')->toArray();
                    $menu_ids = array_merge($menu_ids, $second);
                    $has_menus = array_filter(array_unique($menu_ids));
                    Session::put('has_menus', $has_menus);
                    Session::put('access', $access);
                    Session::put('menus', Menu::with('children')->whereNull('parent_id')->orderBy('order')->get());
                    Session::put('permissions', $permissions);
                    Session::put('administrator', $administrator);
                }
            }
        });
    }
}
