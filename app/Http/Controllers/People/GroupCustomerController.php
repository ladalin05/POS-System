<?php

namespace App\Http\Controllers\People;

use App\DataTables\People\GroupCustomerDataTable;
use App\Http\Controllers\Controller;
use App\Models\People\GroupCustomer;
use Illuminate\Http\Request;

class GroupCustomerController extends Controller
{
    public function index(GroupCustomerDataTable $dataTable)
    {
        return $dataTable->render('people.group_customer.index');
    }

    public function add()
    {
        $title = __('global.add_new');
        $form = new GroupCustomer();
        return view('people.group_customer.form', compact('title', 'form'));
    }
    public function edit($id)
    {
        $title = __('global.edit');
        $form = GroupCustomer::find($id);

        return view('people.group_customer.form', compact('title', 'form'));
    }
    // save user
    public function save(Request $request, $id = null)
    {
        try {
            $request->validate([
                'group_name' => 'required|string|max:255',
                'group_percentage' => 'required|numeric|min:0|max:100',
            ]);

            $data = $request->only(['group_name', 'group_percentage']);

            GroupCustomer::updateOrCreate(['id' => $id], $data);

            return response()->json([
                'status' => 'success',
                'message' => $id ? __('messages.customer_updated') : __('messages.customer_saved'),
                'redirect' => route('people.group_customer.index'),
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
            if ($id == 1) {
                return json([
                    'status' => 'error',
                    'message' => __('messages.user_cannot_delete'),
                ]);
            }
            $form = GroupCustomer::find($id);
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
