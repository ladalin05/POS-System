<?php

namespace App\Http\Controllers\Setting;


use App\DataTables\Setting\RoomDataTable;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Setting\Floor;
use App\Models\Setting\Room;

class RoomController extends Controller
{
    public function index(RoomDataTable $dataTable)
    {
        return $dataTable->render('setting.room.index');
    }

    public function create(Request $request)
    {
        try {

            if ($request->isMethod('get')) {

                $title = __('global.add_new');
                $form = new Room();
                $action = route('setting.rooms.add');

                return response()->json([
                    'title' => $title,
                    'status' => 'success',
                    'message' => 'success',
                    'html' => view('setting.rooms.form', compact('title', 'form', 'action'))->render(),
                    'modal' => 'action-modal',
                ]);
            }

            if ($request->isMethod('post')) {

                $request->validate([
                    'code' => 'required|string|max:255',
                    'name' => 'required|string|max:255',
                    'floor_id' => 'required|integer|exists:floors,id',
                ]);

                Room::create([
                    'code' => $request->code,
                    'name' => $request->name,
                    'floor_id' => $request->floor_id,
                ]);

                return response()->json([
                    'status' => 'success',
                    'message' => __('messages.create_success'),
                    'redirect' => route('setting.rooms.index'),
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

            $form = Room::find($request->id);

            if ($request->isMethod('get')) {

                $title = __('global.edit');
                $action = route('setting.rooms.edit', ['id' => $request->id]);

                return response()->json([
                    'title' => $title,
                    'status' => 'success',
                    'message' => 'success',
                    'html' => view('setting.rooms.form', compact('title', 'form', 'action'))->render(),
                    'modal' => 'action-modal',
                ]);
            }

            if ($request->isMethod('post')) {

                $request->validate([
                    'code' => 'required|string|max:255',
                    'name' => 'required|string|max:255',
                    'floor_id' => 'required|integer|exists:floors,id',
                ]);

                $form->update([
                    'code' => $request->code,
                    'name' => $request->name,
                    'floor_id' => $request->floor_id,
                ]);

                return response()->json([
                    'status' => 'success',
                    'message' => __('messages.update_success'),
                    'redirect' => route('setting.rooms.index'),
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

            $form = Room::find($request->id);

            if (!$form) {
                return response()->json([
                    'status'  => 'error',
                    'message' => 'Room not found',
                ], 404);
            }

            $form->delete();

            return response()->json([
                'status'  => 'success',
                'message' => __('messages.delete_success'),
                'redirect' => route('setting.rooms.index'),
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status'  => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
