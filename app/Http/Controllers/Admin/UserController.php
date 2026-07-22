<?php

namespace App\Http\Controllers\Admin;

use App\DataTables\Admin\UserDataTable;
use App\Http\Controllers\Controller;
use App\Http\Requests\Users\StoreUserRequest;
use App\Http\Requests\Users\UpdateUserRequest;
use App\Models\Admin\Role;
use App\Models\User;
use App\Services\UserService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Throwable;

class UserController extends Controller
{
    protected UserService $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    public function index(UserDataTable $dataTable)
    {
        return $dataTable->render('admin.users.index');
    }

    public function create(Request $request)
    {
        try {
            if ($request->isMethod('post')) {
                $formRequest = app(StoreUserRequest::class);
                $this->userService->save($formRequest->validated(), $formRequest);

                return $this->redirectResponse(
                    message: __('messages.user_saved'),
                    route: route('users-management.users.index'),
                );
            }

            return $this->viewResponse(
                view:   'admin.users.form',
                action: route('users-management.users.create'),
                data:   [
                    'title' => __('global.add_user'),
                    'form' => new User(),
                    ...$this->userService->getFormOptions(),
                ],
            );

        } catch (Throwable $e) {
            return $this->errorResponse($e->getMessage(), 500);
        }
    }

    public function update(Request $request)
    {
        try {
            $form = User::findOrFail($request->id);

            if ($request->isMethod('post')) {
                $formRequest = app(UpdateUserRequest::class);
                $this->userService->save($formRequest->validated(), $formRequest, $request->id);

                return $this->redirectResponse(
                    message: __('messages.user_updated'),
                    route: route('users-management.users.index'),
                );
            }

            return $this->viewResponse(
                view:   'admin.users.form',
                action: route('users-management.users.update', ['id' => $request->id]),
                data:   [
                    'title' => __('global.edit'),
                    'form' => $form,
                    ...$this->userService->getFormOptions($form->id),
                ],
            );

        } catch (Throwable $e) {
            return $this->errorResponse($e->getMessage(), 500);
        }
    }

    public function account()
    {
        $form = new User();
        $roles = Role::all();
        return view('admin.users.account', compact('form', 'roles'));
    }

    public function delete(Request $request)
    {
        try {
            if ((int) $request->id === 1) {
                return $this->errorResponse(__('messages.user_cannot_delete'));
            }

            $form = User::findOrFail($request->id);
            $form->delete();

            return $this->successResponse(__('messages.user_deleted'));
        } catch (Throwable $e) {
            return $this->errorResponse($e->getMessage(), 500);
        }
    }

    public function permission(Request $request)
    {
        try {
            $form = User::find($request->id);
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
        } catch (Throwable $e) {
            return $this->errorResponse($e->getMessage(), 500);
        }
    }

    public function changePassword(Request $request)
    {
        try {
            $form = User::find($request->id);

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

            return $this->errorResponse(__('messages.405'), 405);
        } catch (Throwable $e) {
            return $this->errorResponse($e->getMessage(), 500);
        }
    }
}