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

    public function add()
    {
        $title = __('global.add_new');
        $form = new Room();
        $floor = Floor::all();
        return view('setting.room.form', compact('title', 'form','floor'));
    }
    public function edit($id)
    {
        $title = __('global.edit');
        $form = Room::find($id);
        $floor = Floor::all();

        return view('setting.room.form', compact('title', 'form','floor'));
    }
    // save user
    public function save(Request $request, $id = null)
    {
        try {
            $request->validate([
                'code' => 'required',
                'name' => 'required',
                'floor_id' => 'required'
            ]);
            $data = [
                'code' => $request->code,
                'name' => $request->name,
                'floor_id' => $request->floor_id
            ];
            Room::updateOrCreate(['id' => $id], $data);
            return json([
                'status' => 'success',
                'message' => !empty($id) ? __('messages.user_updated') : __('messages.user_saved'),
                'redirect' => route('setting.room.index'),
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
            $form = Room::find($id);
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
