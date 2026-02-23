<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use App\Models\Admin\Role;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use App\DataTables\Admin\UserDataTable;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
    public function index(UserDataTable $dataTable)
    {
        return $dataTable->render('admin.users.index');
    }
    public function add()
    {
        $title = __('global.add_user');
        $form = new User();
        $roles = Role::all();
        return view('admin.users.form', compact('title', 'form', 'roles'));
    }
    public function account()
    {
        $form = new User();
        $roles = Role::all();
        return view('admin.users.account', compact('form', 'roles'));
    }
    public function edit($id)
    {
        $title = __('global.edit');
        $form = User::find($id);
        $roles = Role::all();
        return view('admin.users.form', compact('title', 'form', 'roles'));
    }
    // save user
    public function save(Request $request, $id = null)
    {
        try {

            $avatar = $request->avatar;
            if(!empty($avatar)){ 
                if ($request->has('avatar') && preg_match('/^data:image\/(\w+);base64,/', $request->avatar, $type)) {
                    $image = substr($request->avatar, strpos($request->avatar, ',') + 1);
                    $image = base64_decode($image);
                    $extension = $type[1];
                    $filename = 'users/' . uniqid() . '.' . $extension;
                    Storage::disk('public')->put($filename, $image);

                    $imageUrl = Storage::url($filename);
                    $imageUrl = str_replace('/storage', '', $imageUrl);
                }
            }
            $request->validate([
                'email' => 'required|email',
                'phone' => 'required',
                'role_id' => 'required',
            ]);
            $username = str_replace('@gmail.com', '', $request->email);
            $data = [
                'name_en' => $request->name_en,
                'name_kh' => $request->name_kh,
                'username' => $username,
                'avatar' => $imageUrl ?? null,
                'email' => $request->email,
                'phone' => $request->phone,
                'address' => $request->address,
                'address_kh' => $request->address_kh,
            ];
            if ($request->password) {
                $request->validate([
                    'password' => 'required|min:6'
                ]);
                $data['password'] = Hash::make($request->password);
            }
            $form = User::updateOrCreate(['id' => $id], $data);
            $form->roles()->sync($request->role_id);
            revoke_session($form->id);
            return json([
                'status' => 'success',
                'message' => !empty($id) ? __('messages.user_updated') : __('messages.user_saved'),
                'redirect' => route('users-management.users.index'),
            ]);
        } catch (\Exception $e) {
            return json([
                'status' => 'error',
                'message' => $e->getMessage(),
            ]);
        }
    }
    public function permission($id)
    {
        try {
            $form = User::find($id);
            $roles = Role::all();
            if (request()->isMethod('get')) {
                return json([
                    'title' => __('global.permission'),
                    'status' => 'success',
                    'message' => 'success',
                    'html' => view('admin.users.permission', compact('form', 'roles'))->render(),
                ]);
            }
            if (request()->isMethod('post')) {
                $form->roles()->sync(request()->role_id);
                return json([
                    'status' => 'success',
                    'message' => __('messages.user_updated'),
                    'redirect' => 'modal',
                    'modal' => 'action-modal',
                ]);
            }
        } catch (\Exception $e) {
            return json([
                'status' => 'error',
                'message' => $e->getMessage(),
            ]);
        }
    }
    // delete user
    public function changePassword($id)
    {
        try {
            $form = User::find($id);
            if (request()->isMethod('get')) {
                return json([
                    'title' => __('global.change_password'),
                    'status' => 'success',
                    'message' => 'success',
                    'html' => view('admin.users.change-password', compact('form'))->render(),
                ]);
            }
            if (request()->isMethod('post')) {
                $request = request();
                $request->validate([
                    'new_password' => 'required|min:6',
                    'confirm_password' => 'required|same:new_password',
                ]);
                $form->password = Hash::make($request->new_password);
                $form->save();
                return json([
                    'status' => 'success',
                    'message' => __('messages.password_changed'),
                    'redirect' => 'modal',
                    'modal' => 'action-modal',
                ]);
            }
            return json([
                'status' => 'error',
                'message' => __('messages.405'),
            ]);
        } catch (\Exception $e) {
            return json([
                'status' => 'error',
                'message' => $e->getMessage(),
            ]);
        }
    }
    public function delete($id)
    {
        try {
            if ($id == 1) {
                return json([
                    'status' => 'error',
                    'message' => __('messages.user_cannot_delete'),
                ]);
            }
            $form = User::find($id);
            $form->delete();
            return json([
                'status' => 'success',
                'message' => __('messages.user_deleted'),
            ]);
        } catch (\Exception $e) {
            return json([
                'status' => 'error',
                'message' => $e->getMessage(),
            ]);
        }
    }
}
