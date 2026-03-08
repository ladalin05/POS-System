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

    public function create(Request $request)
    {
        try {

            if ($request->isMethod('get')) {

                $title = __('global.add_new');
                $form = new Floor();
                $action = route('setting.floors.add');

                return response()->json([
                    'title' => $title,
                    'status' => 'success',
                    'message' => 'success',
                    'html' => view('setting.floors.form', compact('title', 'form', 'action'))->render(),
                    'modal' => 'action-modal',
                ]);
            }

            if ($request->isMethod('post')) {

                $request->validate([
                    'name' => 'required|string|max:255',
                ]);

                Floor::create([
                    'name' => $request->name,
                ]);

                return response()->json([
                    'status' => 'success',
                    'message' => __('messages.create_success'),
                    'redirect' => route('setting.floors.index'),
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
                'message' => $e->getMessage()
            ], 500);

        }
    }
    
    public function update(Request $request)
    {
        try {

            $form = Floor::find($request->id);

            if ($request->isMethod('get')) {

                $title = __('global.edit');
                $action = route('setting.floors.edit', ['id' => $request->id]);

                return response()->json([
                    'title' => $title,
                    'status' => 'success',
                    'message' => 'success',
                    'html' => view('setting.floors.form', compact('title', 'form', 'action'))->render(),
                    'modal' => 'action-modal',
                ]);
            }

            if ($request->isMethod('post')) {

                $request->validate([
                    'name' => 'required|string|max:255',
                ]);

                $form->update([
                    'name' => $request->name,
                ]);

                return response()->json([
                    'status' => 'success',
                    'message' => __('messages.update_success'),
                    'redirect' => route('setting.floors.index'),
                    'modal' => 'action-modal',
                ]);
            }

        } catch (\Exception $e) {

            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);

        }
    }

    public function delete(Request $request)
    {
        try {

            $form = Floor::find($request->id);

            if (!$form) {
                return response()->json([
                    'status'  => 'error',
                    'message' => 'Floor not found',
                ], 404);
            }

            $form->delete();

            return response()->json([
                'status'  => 'success',
                'message' => __('messages.delete_success'),
                'redirect' => route('setting.floors.index'),
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status'  => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
