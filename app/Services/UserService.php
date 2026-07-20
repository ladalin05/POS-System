<?php

namespace App\Services;

use App\Models\Admin\Role;
use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class UserService
{

    public function getFormOptions(?int $id = null): array
    {
        return [
            'roles' => Role::all(),
        ];
    }

    public function save(array $validated, FormRequest $request, ?int $id = null): User
    {
        $imageUrl = null;

        if ($request->hasFile('avatar')) {
            $file = $request->file('avatar');
            $filename = 'images/users/' . uniqid() . '.' . $file->getClientOriginalExtension();
            Storage::disk('public')->put($filename, file_get_contents($file));
            $imageUrl = Storage::url($filename);
        }

        $username = str_replace('@gmail.com', '', $validated['email']);

        $data = [
            'name_en' => $validated['name_en'] ?? null,
            'name_kh' => $validated['name_kh'] ?? null,
            'username' => $username,
            'email' => $validated['email'],
            'phone' => $validated['phone'],
            'address' => $validated['address'] ?? null,
            'address_kh' => $validated['address_kh'] ?? null,
        ];

        if ($imageUrl) {
            $data['avatar'] = $imageUrl;
        }

        if (!empty($validated['password'])) {
            $data['password'] = Hash::make($validated['password']);
        }

        $form = User::updateOrCreate(['id' => $id], $data);
        $form->roles()->sync($validated['role_id']);

        revoke_session($form->id);

        return $form;
    }
}