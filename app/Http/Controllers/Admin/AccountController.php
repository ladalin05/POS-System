<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;

class AccountController extends Controller
{
    public function changePassword()
    {
        try {
            $form = auth();
            if (request()->isMethod('get')) {
                return json([
                    'title' => __('global.change_password'),
                    'status' => 'success',
                    'message' => 'success',
                    'html' => view('admin.users.account-password', compact('form'))->render(),
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
}
