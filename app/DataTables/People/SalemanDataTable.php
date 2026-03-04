<?php

namespace App\DataTables\People;

use App\Models\People\Saleman;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Services\DataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;

class SalemanDataTable extends DataTable
{
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return datatables()
            ->eloquent($query)
            ->addIndexColumn()
            ->addColumn('action', fn ($row) => view('people.saleman.action', compact('row')))
            ->addColumn('full_name', fn ($row) => $row->first_name . ' ' . $row->last_name)
            ->addColumn('group_name', fn ($row) => $row->group->group_name ?? '-')
            ->rawColumns(['action'])
            ->setRowId('id');
    }

    public function query(Saleman $model): QueryBuilder
    {
        return $model->newQuery()->with(['group']);
    }

    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('salesman-table')
            ->columns($this->getColumns())
            ->minifiedAjax()
            ->orderBy(1)
            ->selectStyleSingle()
            ->buttons([
                Button::make('excel'),
                Button::make('csv'),
                Button::make('pdf'),
                Button::make('print'),
                Button::make('reset'),
                Button::make('reload'),
            ]);
    }

    public function getColumns(): array
    {
        return [
            Column::computed('DT_RowIndex')
                ->title(__('No'))
                ->width(60)
                ->addClass('text-center'),
            Column::make('full_name')->title('Full Name'),
            Column::make('gender'),
            Column::make('phone'),
            Column::make('position'),
            Column::make('group_name')->title('Group'),
            Column::make('status'),
            Column::computed('action')
                ->exportable(false)
                ->printable(false)
                ->width(60)
                ->addClass('text-center'),
        ];
    }

    protected function filename(): string
    {
        return 'Salesman_' . date('YmdHis');
    }
}
