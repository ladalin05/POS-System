<?php

namespace App\Http\Controllers\People;

use App\DataTables\People\GroupSalemanDataTable;
use App\Http\Controllers\Controller;
use App\Models\People\GroupSaleman;
use Illuminate\Http\Request;

class GroupSalemanController extends Controller
{
    public function index(GroupSalemanDataTable $dataTable)
    {
        return $dataTable->render('people.group_saleman.index');
    }

    public function add()
    {
        $title = __('global.add_new');
        $form = new GroupSaleman();
        return view('people.group_saleman.form', compact('title', 'form'));
    }
    public function edit($id)
    {
        $title = __('global.edit');
        $form = GroupSaleman::find($id);

        return view('people.group_saleman.form', compact('title', 'form'));
    }
    // save user
    public function save(Request $request, $id = null)
    {
        try {
            $request->validate([
                'group_name' => 'required|string|max:255',
                'description' => 'nullable|string',
            ]);

            $data = $request->only(['group_name', 'description']);

            GroupSaleman::updateOrCreate(['id' => $id], $data);

            return response()->json([
                'status' => 'success',
                'message' => $id ? __('messages.saleman_updated') : __('messages.saleman_saved'),
                'redirect' => route('people.group_saleman.index'),
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
            $form = GroupSaleman::find($id);
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
