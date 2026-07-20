<?php

namespace App\Http\Controllers\Setting;

use App\DataTables\Setting\RoomDataTable;
use App\Http\Controllers\Controller;
use App\Http\Requests\Setting\StoreRoomRequest;
use App\Http\Requests\Setting\UpdateRoomRequest;
use App\Models\Setting\Floor;
use App\Models\Setting\Room;
use App\Services\BaseService;
use Illuminate\Http\Request;

class RoomController extends Controller
{
    private BaseService $service;

    public function __construct()
    {
        $this->service = new class extends BaseService {
            protected function getQuery() { return Room::query(); }
        };
    }

    public function index(RoomDataTable $dataTable)
    {
        return $dataTable->render('setting.room.index');
    }

    public function create(Request $request)
    {
        try {

            if ($request->isMethod('post')) {
                $formRequest = app(StoreRoomRequest::class);
                $data = $formRequest->validated();

                $this->service->create($data);

                return $this->redirectResponse(
                    message: __('messages.create_success'),
                    route: route('setting.rooms.index'),
                );
            }

            return $this->modalResponse(
                title:  __('global.add_new'),
                view:   'setting.rooms.form',
                data:   [
                    'form' => new Room(),
                    'floors' => Floor::pluck('name', 'id'),
                ],
                action: route('setting.rooms.add'),
            );

        } catch (\Throwable $e) {
            return $this->errorResponse(
                message: $e->getMessage(),
                code: 500,
            );
        }
    }

    public function update(Request $request)
    {
        try {
            $room = Room::findOrFail($request->id);

            if ($request->isMethod('post')) {
                $formRequest = app(UpdateRoomRequest::class);
                $data = $formRequest->validated();

                $this->service->update($data, $room->id);

                return $this->redirectResponse(
                    message: __('messages.update_success'),
                    route: route('setting.rooms.index'),
                );
            }

            return $this->modalResponse(
                title:  __('global.edit'),
                view:   'setting.rooms.form',
                data:   [
                    'form' => $room,
                    'floors' => Floor::pluck('name', 'id'),
                ],
                action: route('setting.rooms.edit', ['id' => $room->id]),
            );

        } catch (\Throwable $e) {
            return $this->errorResponse(
                message: $e->getMessage(),
                code: 500,
            );
        }
    }

    public function delete(Request $request)
    {
        try {
            $room = Room::findOrFail($request->id);
            $room->delete();

            return $this->redirectResponse(
                message: __('messages.delete_success'),
                route: route('setting.rooms.index'),
            );
        } catch (\Throwable $e) {
            return $this->errorResponse(
                message: $e->getMessage(),
                code: 500,
            );
        }
    }
}