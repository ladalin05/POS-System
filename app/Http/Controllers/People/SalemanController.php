<?php

namespace App\Http\Controllers\People;

use App\DataTables\People\SalemanDataTable;
use App\Http\Controllers\Controller;
use App\Models\People\Saleman;
use App\Models\People\GroupSaleman;
use Illuminate\Http\Request;

class SalemanController extends Controller
{
    public function index(SalemanDataTable $dataTable)
    {
        return $dataTable->render('people.saleman.index');
    }

    public function add()
    {
        $title = __('global.add_new');
        $form = new Saleman();
        $group_options = GroupSaleman::select('id', 'group_name as name')->get();
        $gender = (object) [
            (object) ['id' => 'Male', 'name' => 'Male'],
            (object) ['id' => 'Female', 'name' => 'Female'],
        ];
        $status = (object) [
            (object) ['id' => 'Active', 'name' => 'Active'],
            (object) ['id' => 'Inactive', 'name' => 'Inactive'],
        ];
        return view('people.saleman.form', compact('title', 'form', 'group_options', 'gender', 'status'));
    }

    public function edit($id)
    {
        $title = __('global.edit');
        $form = Saleman::find($id);
        $group_options = GroupSaleman::select('id', 'group_name as name')->get();
        $gender = (object) [
            (object) ['id' => 'Male', 'name' => 'Male'],
            (object) ['id' => 'Female', 'name' => 'Female'],
        ];
        $status = (object) [
            (object) ['id' => 'Active', 'name' => 'Active'],
            (object) ['id' => 'Inactive', 'name' => 'Inactive'],
        ];
        return view('people.saleman.form', compact('title', 'form', 'group_options', 'gender', 'status'));
    }

    public function save(Request $request, $id = null)
    {
        try {
            $request->validate([
                'first_name' => 'required|string|max:255',
                'last_name' => 'required|string|max:255',
                'gender' => 'required|in:Male,Female',
                'phone' => 'nullable|string|max:20',
                'position' => 'nullable|string|max:255',
                'group_id' => 'nullable|exists:group_saleman,id',
                'status' => 'required|in:Active,Inactive',
            ]);
            $data = $request->only([
                'first_name',
                'last_name',
                'gender',
                'phone',
                'position',
                'group_id',
                'status',
            ]);

            Saleman::updateOrCreate(['id' => $id], $data);

            return response()->json([
                'status' => 'success',
                'message' => $id ? __('messages.saleman_updated') : __('messages.saleman_saved'),
                'redirect' => route('people.saleman.index'),
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
            $form = Saleman::findOrFail($id);
            $form->delete();

            return response()->json([
                'status' => 'success',
                'message' => __('messages.saleman_deleted'),
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage(),
            ]);
        }
    }
}
