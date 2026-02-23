<?php

namespace App\Http\Controllers\Setting;

use App\DataTables\Setting\FloorDataTable;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Setting\Floor;

class FloorController extends Controller
{
    public function index(FloorDataTable $dataTable)
    {
        return $dataTable->render('setting.floor.index');
    }

    public function add()
    {
        $title = __('global.add_new');
        $form = new Floor();
        // $projects = Project::all();
        return view('setting.floor.form', compact('title', 'form'));
    }
    public function edit($id)
    {
        $title = __('global.edit');
        $form = Floor::find($id);

        return view('setting.floor.form', compact('title', 'form'));
    }
    // save user
    public function save(Request $request, $id = null)
    {
        try {
            $request->validate([
                'name' => 'required',
            ]);
            $data = [
                'name' => $request->name
            ];
            Floor::updateOrCreate(['id' => $id], $data);
            return json([
                'status' => 'success',
                'message' => !empty($id) ? __('messages.user_updated') : __('messages.user_saved'),
                'redirect' => route('setting.floor.index'),
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
            $form = Floor::find($id);
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
