<?php

namespace App\Http\Controllers\Other;

use App\DataTables\Other\CashAccountDataTable;
use App\Http\Controllers\Controller;
use App\Models\Other\CashAccount;
use Illuminate\Http\Request;
use App\Models\Product\product;


class CashAccountController extends Controller
{
    public function index(CashAccountDataTable $dataTable)
    {
        return $dataTable->render('other.cash_accounts.index');
    }
    public function add()
    {
        $title = __('global.add_new');
        $form = new CashAccount();
        return view("other.cash_accounts.form", compact('title', 'form'));
    }
    public function edit($id)
    {
        $title = __('global.edit');
        $form = CashAccount::find($id);
        $product = product::all();
        return view("other.cash_accounts.form", compact('title', 'form'));
    }
    public function save(Request $request, $id = null)
    {
        try {
            $request->validate([
                'code' => 'required|string|max:255',
                'name' => 'required|string|max:20',
                'type' => 'required|string',
            ]);
            $data = [
                'code' => $request->code,
                'name' => $request->name,
                'type' => $request->type,
            ];
           
            CashAccount::updateOrCreate(['id' => $id], $data);
            return json([
                'status' => 'success',
                'message' => !empty($id) ? __('messages.updated') : __('messages.saved'),
                'redirect' => route('other.cash_accounts.index'),
            ]);
        } catch (\Exception $e) {
            return json([
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
            $form = CashAccount::find($id);
            $form->delete();
            return json([
                'status' => 'success',
                'message' => __('messages.deleted'),
            ]);
        } catch (\Exception $e) {
            return json([
                'status' => 'error',
                'message' => $e->getMessage(),
            ]);
        }
    }
}
