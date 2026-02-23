<?php

namespace App\Http\Controllers\Other;

use App\DataTables\Other\BrancheDataTable;
use App\Http\Controllers\Controller;
use App\Models\Other\Branch; // <-- create this model/table
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class BranchController extends Controller
{
    public function index(BrancheDataTable $dataTable)
    {
        return $dataTable->render('other.branch.index');
    }

    /**
     * Open "Add Branch" modal (returns the form partial HTML)
     */
    public function add()
    {
        try {
            $form = new Branch();
            $title = __('global.add_new');

            return response()->json([
                'title' => $title,
                'status' => 'success',
                'message' => 'success',
                'html' => view('other.branch.form', compact('title', 'form'))->render(),
                'modal' => 'action-modal',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage(),
            ]);
        }
    }

    /**
     * Open "Edit Branch" modal (returns the form partial HTML)
     */
    public function edit($id)
    {
        $form = Branch::findOrFail($id);
        $title = __('global.edit');

        return response()->json([
            'title' => $title,
            'status' => 'success',
            'message' => 'success',
            'html' => view('other.branch.form', compact('title', 'form'))->render(),
        ]);
    }

    public function save(Request $request, $id = null)
    {

        try {
            if ($request->isMethod('get')) {
                $form = $id ? Branch::findOrFail($id) : new Branch();
                $title = $id ? __('global.edit') : __('global.add_new');

                $action = route('other.branch.save', ['id' => $id]);

                return response()->json([
                    'title' => $title,
                    'status' => 'success',
                    'message' => 'success',
                    'html' => view('other.branch.form', compact('title', 'form', 'action'))->render(),
                    'modal' => 'action-modal',
                ]);
            }
            if (request()->isMethod('post')) {
                $rules = [
                    'name' => 'required|string|max:255',
                    'name_kh' => 'nullable|string|max:255',
                    'phone' => 'required|string|max:100',
                    'phone_kh' => 'nullable|string|max:100',
                    'address' => 'required|string|max:1000',
                    'address_kh' => 'nullable|string|max:1000',
                    'city' => 'required|string|max:120',
                    'city_kh' => 'nullable|string|max:120',
                    'country' => 'nullable|string|max:120',
                    'country_kh' => 'nullable|string|max:120',
                    'vat_number' => 'nullable|string|max:120',
                    'vat_number_kh' => 'nullable|string|max:120',
                    'email' => 'required|email|max:255',
                    'prefix' => 'nullable|string|max:50',
                    // 'default_cash'  => 'required|string|in:Cash,Card,Bank',
                    'working_day' => 'nullable|integer|min:0',
                    'invoice_footer' => 'nullable|string|max:2000',
                    'logo' => 'nullable|image|max:2048',
                ];
                $data = $request->validate($rules);


              
                if ($request->hasFile('logo')) {
                    $logo = $request->file('logo');
                    $filename = time() . '_' . $logo->getClientOriginalName();
                    $logo->move(public_path('uploads/branch_logos'), $filename);
                    $data['logo'] = 'uploads/branch_logos/' . $filename;
                }


                // Save
                Branch::updateOrCreate(['id' => $id], $data);


                return response()->json([
                    'status' => 'success',
                    'message' => __('messages.user_saved'),
                    'redirect' => route('other.branch.index'),
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
            $branch = Branch::findOrFail($id);
            $branch->delete();

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

            Branch::whereIn('id', $ids)->delete();

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
