<?php

namespace App\Http\Controllers\Expense;
use App\DataTables\Expense\ExpenseCategoryDataTable;
use App\Http\Controllers\Controller;
use App\Models\Expense\Expense;
use App\Models\Expense\ExpenseCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ExpenseCategoryController extends Controller
{
    public function index(ExpenseCategoryDataTable $dataTable)
    {
        return $dataTable->render('expense.expense_category.index');
    }

    public function add()
    {
        try {
            $form = new ExpenseCategory(); 
            $title = __('global.add_new');

            return response()->json([
                'title' => $title,
                'status' => 'success',
                'message' => 'success',
                'html' => view('expense.expense_category.form', compact('title', 'form'))->render(),
                'modal' => 'action-modal',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage(),
            ]);
        }
    }

   
    public function edit($id)
    {
        $form = ExpenseCategory::findOrFail($id);
        $title = __('global.edit');

        return response()->json([
            'title' => $title,
            'status' => 'success',
            'message' => 'success',
            'html' => view('expense.expense_category.form', compact('title', 'form'))->render(),
        ]);
    }

    public function save(Request $request, $id = null)
    {

        try {
            if ($request->isMethod('get')) {
                $form = $id ? ExpenseCategory::findOrFail($id) : new ExpenseCategory();
                $title = $id ? __('global.edit') : __('global.add_new');

                $action = route('expense.expense_category.save', ['id' => $id]);

                return response()->json([
                    'title' => $title,
                    'status' => 'success',
                    'message' => 'success',
                    'html' => view('expense.expense_category.form', compact('title', 'form', 'action'))->render(),
                    'modal' => 'action-modal',
                ]);
            }
            if (request()->isMethod('post')) {
                $rules = [
                    'code' => 'required|string|max:255',
                    'name' => 'nullable|string|max:255',
                    'description' => 'nullable|string|max:255',
                ];
                $data = $request->validate($rules);

                ExpenseCategory::updateOrCreate(['id' => $id], $data);

                return response()->json([
                    'status' => 'success',
                    'message' => __('messages.user_saved'),
                    'redirect' => route('expense.expense_category.index'),
                    'modal' => 'action-modal',

                ]);
            }

            return response()->json([
                'status' => 'error',
                'message' => __('messages.405'),
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage(),
            ]);
        }
    }

    public function delete($id)
    {
        try {
            $Expense = ExpenseCategory::findOrFail($id);
            $Expense->delete();

            return response()->json([
                'status' => 'success',
                'message' => __('messages.user_deleted'),
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage(),
            ]);
        }
    }

    public function bulkDelete(Request $request)
    {
        try {
            $ids = $request->input('ids', []);
            if (empty($ids)) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'No items selected.',
                ]);
            }

            ExpenseCategory::whereIn('id', $ids)->delete();

            return response()->json([
                'status' => 'success',
                'message' => __('messages.user_deleted'),
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage(),
            ]);
        }
    }
}
