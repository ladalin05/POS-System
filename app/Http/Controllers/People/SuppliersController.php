<?php

namespace App\Http\Controllers\People;

use App\DataTables\People\SuppliersDataTable;
use App\Http\Controllers\Controller;
use App\Models\People\Suppliers;
use Illuminate\Http\Request;

class SuppliersController extends Controller
{
    public function index(SuppliersDataTable $dataTable)
    {
        return $dataTable->render('people.suppliers.index');
    }

    public function add()
    {
        $title = __('global.add_new');
        $form = new Suppliers();
        return view('people.suppliers.form', compact('title', 'form'));
    }
    public function edit($id)
    {
        $title = __('global.edit');
        $form = Suppliers::find($id);

        return view('people.suppliers.form', compact('title', 'form'));
    }
    // save user
    public function save(Request $request, $id = null)
    {
        try {
            $request->validate([
                'code' => 'required',
                'company' => 'required',
                'name' => 'required',
                'phone' => 'required',
                'address' => 'required',
                'city' => 'required',
                'state' => 'required',
                'email_address' => 'required|email',
                'vat_number' => 'required',
                'postal_code' => 'required',
                'country' => 'required',
            ]);

            $data = [
                'code' => $request->code,
                'company' => $request->company,
                'name' => $request->name,
                'phone' => $request->phone,
                'address' => $request->address,
                'city' => $request->city,
                'state' => $request->state,
                'email_address' => $request->email_address,
                'vat_number' => $request->vat_number,
                'postal_code' => $request->postal_code,
                'country' => $request->country,
            ];

            Suppliers::updateOrCreate(['id' => $id], $data);

            return response()->json([
                'status' => 'success',
                'message' => $id ? __('messages.suppliers_updated') : __('messages.suppliers_saved'),
                'redirect' => route('people.suppliers.index'),
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
            $form = Suppliers::find($id);
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
