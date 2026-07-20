<?php

namespace App\Http\Controllers\Other;

use App\DataTables\Other\BrancheDataTable;
use App\Http\Controllers\Controller;
use App\Http\Requests\Other\StoreBranchRequest;
use App\Http\Requests\Other\UpdateBranchRequest;
use App\Models\Other\Branch;
use App\Services\BaseService;
use Illuminate\Http\Request;
use Throwable;

class BranchController extends Controller
{
    private BaseService $service;

    public function __construct()
    {
        $this->service = new class extends BaseService {
            protected function getQuery() { return Branch::query(); }
        };
    }

    public function index(BrancheDataTable $dataTable)
    {
        return $dataTable->render('other.branch.index');
    }

    public function create(Request $request)
    {
        try {
            if ($request->isMethod('post')) {
                $formRequest = app(StoreBranchRequest::class);
                $data = $formRequest->validated();

                if ($request->hasFile('logo')) {
                    $data['logo'] = $this->storeLogo($request);
                }

                $this->service->create($data);

                return $this->redirectResponse(
                    message: __('messages.create_successfully'),
                    route: route('other.branch.index'),
                );
            }

            return $this->viewResponse(
                view:   'other.branch.form',
                action: route('other.branch.create'),
                data:   [
                    'title' => __('global.add_new'),
                    'form' => new Branch(),
                ],
            );

        } catch (Throwable $e) {
            return $this->errorResponse(
                message: $e->getMessage(),
                code: 500,
            );
        }
    }

    public function update(Request $request)
    {
        try {
            $form = Branch::findOrFail($request->id);

            if ($request->isMethod('post')) {
                $formRequest = app(UpdateBranchRequest::class);
                $data = $formRequest->validated();

                if ($request->hasFile('logo')) {
                    $data['logo'] = $this->storeLogo($request);
                }

                $this->service->update($data, $request->id);

                return $this->redirectResponse(
                    message: __('messages.update_successfully'),
                    route: route('other.branch.index'),
                );
            }

            return $this->viewResponse(
                view:   'other.branch.form',
                action: route('other.branch.update', ['id' => $request->id]),
                data:   [
                    'title' => __('global.edit'),
                    'form' => $form,
                ],
            );

        } catch (Throwable $e) {
            return $this->errorResponse(
                message: $e->getMessage(),
                code: 500,
            );
        }
    }

    public function delete($id)
    {
        try {
            $form = Branch::findOrFail($id);
            $form->delete();

            return $this->redirectResponse(
                message: __('messages.delete_branch_successfully'),
                route: route('other.branch.index'),
            );
        } catch (Throwable $e) {
            return $this->errorResponse(
                message: $e->getMessage(),
                code: 500,
            );
        }
    }

    public function bulkDelete(Request $request)
    {
        try {
            $ids = $request->input('ids', []);

            if (empty($ids)) {
                return $this->errorResponse(
                    message: 'No items selected.',
                    code: 422,
                );
            }

            Branch::whereIn('id', $ids)->delete();

            return $this->redirectResponse(
                message: __('messages.delete_branch_successfully'),
                route: route('other.branch.index'),
            );
        } catch (Throwable $e) {
            return $this->errorResponse(
                message: $e->getMessage(),
                code: 500,
            );
        }
    }

    protected function storeLogo(Request $request): string
    {
        $logo = $request->file('logo');
        $filename = time() . '_' . $logo->getClientOriginalName();
        $logo->move(public_path('uploads/branch_logos'), $filename);

        return 'uploads/branch_logos/' . $filename;
    }
}