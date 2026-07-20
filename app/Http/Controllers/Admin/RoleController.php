<?php

namespace App\Http\Controllers\Admin;

use App\DataTables\Admin\RoleDataTable;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreRoleRequest;
use App\Http\Requests\Admin\UpdateRoleRequest;
use App\Models\Admin\Role;
use App\Services\RoleService;
use Illuminate\Http\Request;
use Throwable;

class RoleController extends Controller
{
    protected RoleService $roleService;

    public function __construct(RoleService $roleService)
    {
        $this->roleService = $roleService;
    }

    public function index(RoleDataTable $dataTable)
    {
        return $dataTable->render('admin.roles.index');
    }

    public function create(Request $request)
    {
        try {
            if ($request->isMethod('post')) {
                $formRequest = app(StoreRoleRequest::class);
                $this->roleService->save($formRequest->validated(), $formRequest);

                return $this->redirectResponse(
                    message: __('messages.role_saved'),
                    route: route('users-management.roles.index'),
                );
            }

            return $this->viewResponse(
                view:   'admin.roles.form',
                action: route('users-management.roles.create'),
                data:   [
                    'title' => __('global.add_new'),
                    'form' => new Role(),
                    ...$this->roleService->getFormOptions(),
                ],
            );

        } catch (Throwable $e) {
            return $this->errorResponse($e->getMessage(), 500);
        }
    }

    public function update(Request $request)
    {
        try {
            $form = Role::findOrFail($request->id);

            if ($request->isMethod('post')) {
                $formRequest = app(UpdateRoleRequest::class);
                $this->roleService->save($formRequest->validated(), $formRequest, $request->id);

                return $this->redirectResponse(
                    message: __('messages.role_updated'),
                    route: route('users-management.roles.index'),
                );
            }

            return $this->viewResponse(
                view:   'admin.roles.form',
                action: route('users-management.roles.update', ['id' => $request->id]),
                data:   [
                    'title' => __('global.edit'),
                    'form' => $form,
                    ...$this->roleService->getFormOptions($form->id),
                ],
            );

        } catch (Throwable $e) {
            return $this->errorResponse($e->getMessage(), 500);
        }
    }

    public function permission($id)
    {
        return view('admin.roles.permission');
    }

    public function delete($id)
    {
        try {
            if ((int) $id === 1) {
                return $this->errorResponse(__('messages.role_cannot_delete'));
            }

            $role = Role::findOrFail($id);
            $role->delete();

            return $this->successResponse(__('messages.role_deleted'));
        } catch (Throwable $e) {
            return $this->errorResponse($e->getMessage(), 500);
        }
    }
}