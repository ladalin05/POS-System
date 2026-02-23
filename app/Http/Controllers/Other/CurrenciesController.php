<?php

namespace App\Http\Controllers\Other;

use App\DataTables\Other\CurrenciesDataTable;
use App\Http\Controllers\Controller;
use App\Models\Other\currencies; // <-- create this model/table
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CurrenciesController extends Controller
{
    public function index(CurrenciesDataTable $dataTable)
    {
        return $dataTable->render('other.currencies.index');
    }

    public function add()
    {
        try {
            $form = new currencies(); 
            $title = __('global.add_new');

            return response()->json([
                'title' => $title,
                'status' => 'success',
                'message' => 'success',
                'html' => view('other.currencies.form', compact('title', 'form'))->render(),
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
        $form = Currencies::findOrFail($id);
        $title = __('global.edit');

        return response()->json([
            'title' => $title,
            'status' => 'success',
            'message' => 'success',
            'html' => view('other.currencies.form', compact('title', 'form'))->render(),
        ]);
    }

    public function save(Request $request, $id = null)
    {

        try {
            if ($request->isMethod('get')) {
                $form = $id ? Currencies::findOrFail($id) : new Currencies();
                $title = $id ? __('global.edit') : __('global.add_new');

                $action = route('other.currencies.save', ['id' => $id]);

                return response()->json([
                    'title' => $title,
                    'status' => 'success',
                    'message' => 'success',
                    'html' => view('other.currencies.form', compact('title', 'form', 'action'))->render(),
                    'modal' => 'action-modal',
                ]);
            }
            if (request()->isMethod('post')) {
                $rules = [
                    'code' => 'required|string|max:255',
                    'name' => 'nullable|string|max:255',
                    'rate' => 'required|string|max:100',
                ];
                $data = $request->validate($rules);

                currencies::updateOrCreate(['id' => $id], $data);

                return response()->json([
                    'status' => 'success',
                    'message' => __('messages.user_saved'),
                    'redirect' => route('other.currencies.index'),
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
            $currencies = Currencies::findOrFail($id);
            $currencies->delete();

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

            Currencies::whereIn('id', $ids)->delete();

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
