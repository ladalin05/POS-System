<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="ltr">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Room Booking</title>
    <link rel="stylesheet" href="{{ asset('assets/css/all.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/main.css') }}">

    <style>
        body {
            font-family: 'Noto Sans', sans-serif;
        }

        .room-box {
            height: 100px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            border-radius: 0.5rem;
            font-size: 14px;
            font-weight: 500;
            text-decoration: none;
        }

        .room-box:hover {
            opacity: 0.85;
        }

        .room-disabled {
            pointer-events: none;
            cursor: not-allowed;
        }
    </style>
</head>

<body>
    <div class="container-fluid mt-4">
        <div class="card">
            <div class="card-header" style="background-color:#0d5c9d !important;color:aliceblue">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Room Booking</h5>

                    @if(request()->has('move'))
                        <div style="color:#fff; font-weight:600; background:#d97706; padding:6px 10px; border-radius:6px;">
                            <i class="fa fa-exchange-alt"></i>&nbsp; Move mode — select target room
                            <a href="{{ route('pos.table.addTable') }}" style="margin-left:8px; color:#fff; opacity:.9">
                                <i class="fa fa-times-circle"></i> Cancel
                            </a>
                        </div>
                    @elseif(!empty($selectedRoomName))
                        <a href="{{ route('pos.table.addTable', ['clear' => 1]) }}" class="btn btn-sm"
                            style="background:transparent;color:#fff;border:1px solid rgba(255,255,255,0.2);padding:6px 10px;border-radius:6px;">
                            <i class="fa fa-times-circle"></i>&nbsp; Unselect ({{ $selectedRoomName }})
                        </a>
                    @endif
                </div>
            </div>

            <div class="card-body">

                <!-- Filter Form -->
                <form method="GET" action="{{ route('pos.table.addTable') }}">
                    <div class="row mb-4">

                        <div class="col-md-4">
                            <label for="customer_id" class="form-label">Customer</label>
                            <select class="form-select" name="customer_id" id="customer_id">
                                @foreach($customers as $customer)
                                    <option value="{{ $customer->id }}">{{ $customer->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-4">
                            <label for="floor" class="form-label">Floor</label>
                            <select class="form-select" name="floor" id="floor" onchange="this.form.submit()">
                                <option value="">Select Floor</option>
                                @foreach($floors as $floor)
                                    <option value="{{ $floor->id }}" {{ isset($floorId) && $floorId == $floor->id ? 'selected' : '' }}>
                                        {{ $floor->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>


                        <div class="col-md-4">
                            <label for="warehouses" class="form-label">warehouses</label>
                            <select class="form-select" name="warehouses" id="warehouses">
                                @foreach($warehouses as $warehouse)
                                    <option value="{{ $warehouse->id }}">{{ $warehouse->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </form>

                <!-- Room Display -->
                <div class="row">
                    @forelse($rooms as $room)
                        @php
                            $roomNumber = str_pad($room->name, 3, '0', STR_PAD_LEFT);
                            $isOccupied = $room->status === 'occupied';
                            $isSelected = isset($selectedRoomId) && $selectedRoomId == $room->id;
                            $bgColor = $isOccupied ? '#77021d' : ($isSelected ? 'red' : '#007378');
                            $inMoveMode = request()->has('move');
                        @endphp
                        <div class="col-md-1 mb-3">
                            @if($isOccupied)
                                {{-- occupied rooms are not clickable --}}
                                <div class="room-box text-white text-center p-3 room-disabled"
                                    style="background-color: {{ $bgColor }};" title="Occupied">
                                    <div style="font-size: 12px;">
                                        <i class="fa fa-clock"></i> 10:21 PM<br>(290 mins ago)
                                    </div>
                                    <div class="fw-bold">{{ $roomNumber }}</div>
                                    <div>$0.00</div>
                                </div>
                            @else
                                            {{-- clickable room --}}
                                            <a href="{{ $inMoveMode
                                ? route('pos.moveRoom', ['room_id' => $room->id])
                                : route('pos.index', ['room_id' => $room->id]) }}"
                                                class="room-box text-white text-center p-3" style="background-color: {{ $bgColor }};"
                                                data-room-id="{{ $room->id }}">
                                                <div class="fw-bold">{{ $roomNumber }}</div>
                                            </a>
                            @endif
                        </div>
                    @empty
                        <div class="col-12">
                            <p class="text-muted">No rooms found for this floor.</p>
                        </div>
                    @endforelse

                </div>

            </div>
        </div>
    </div>
</body>

</html>