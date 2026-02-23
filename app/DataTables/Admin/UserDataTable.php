<?php

namespace App\DataTables\Admin;

use App\Models\User;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Column;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\Services\DataTable;

class UserDataTable extends DataTable
{
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return datatables()
            ->eloquent($query)
            ->addIndexColumn()
            ->editColumn('avatar', function ($user) {
                $img = $user->avatar ? Storage::url($user->avatar) : 'https://ui-avatars.com/api/?name=' . urlencode($user->name_en) . '&background=random';
                return '<div class="d-flex align-items-center">
                            <img src="'.$img.'" class="rounded me-3" style="width: 40px; height: 40px; object-fit: cover;">
                        </div>';
            })
            ->editColumn('role', function ($user) {
                return '<span class="text-muted">' . ($user->roles->first()->name ?? 'No Role') . '</span>';
            })
            ->editColumn('status', function ($user) {
                return '<span class="status-badge-active">
                            <span class="dot"></span> Active
                        </span>';
            })
            ->addColumn('action', fn ($user) => view('admin.users.action', compact('user')))
            ->rawColumns(['avatar', 'role', 'status', 'action'])
            ->setRowId('id');
    }

    public function query(User $model): QueryBuilder
    {
        $query = $model->newQuery()->select('id', 'name_en', 'name_kh', 'email', 'avatar', 'phone', 'created_at')->with('roles');

        return $query;
    }

    public function html(): HtmlBuilder
    {
        return $this->builder()
                ->setTableId('user-table')
                ->columns($this->getColumns())
                ->minifiedAjax()
                ->orderBy(1)
                ->parameters([
                    'buttons' => [],
                ]);
    }

    public function getColumns(): array
    {
        return [
            Column::computed('DT_RowIndex', __('global.n_o'))->width(60)->addClass('text-center'),
            Column::make('avatar')->title(__('Image'))->orderable(false),
            Column::make('name_en')->title(__('Name EN'))->orderable(false),
            Column::make('name_kh')->title(__('Name KH'))->orderable(false),
            Column::make('phone')->title(__('Phone'))->orderable(false),
            Column::make('email')->title(__('Email'))->orderable(false),
            Column::make('role')->title(__('Role'))->orderable(false),
            Column::make('status')->title(__('Status'))->orderable(false),
            Column::computed('action')->title('')->orderable(false),
        ];
    }
}