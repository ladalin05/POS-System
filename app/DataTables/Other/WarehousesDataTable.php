<?php

namespace App\DataTables\Other;

use App\Models\Other\Branch;
use App\Models\Other\Warehouses;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Services\DataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;

class WarehousesDataTable extends DataTable
{
    /**
     * Build the DataTable class.
     *
     * @param QueryBuilder $query Results from query() method.
     */
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->addColumn('checkbox', function ($a) {
                return '<input type="checkbox" class="row-checkbox" value="' . $a->id . '">';
            })
            ->addColumn('action', fn($a) => view('other.warehouses.action', compact('a')))
            ->rawColumns(['checkbox', 'action'])
            ->setRowId('id');
    }

    /**
     * Get the query source of dataTable.
     */
    public function query(Warehouses $model): QueryBuilder
    {
        // Select only columns we show to keep payload light
        return $model->newQuery()->with('branch');
    }

    /**
     * Optional method if you want to use the html builder.
     */
    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('branch-table')
            ->columns($this->getColumns())
            ->minifiedAjax()
            ->orderBy(1) // order by Code
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

    /**
     * Get the dataTable columns definition.
     */
    public function getColumns(): array
    {
        return [
            Column::computed('checkbox')
                ->exportable(false)
                ->printable(false)
                ->width(30)
                ->title('<input type="checkbox" id="select-all">')
                ->addClass('text-center'),

            Column::make('id')->title('ID')->width(60),
            Column::make('code')->title(__('global.code')),
            Column::make('name')->title(__('global.name')),
            Column::make('phone')->title(__('global.phone')),
            Column::make('email')->title(__('global.email_address')),
            Column::make('address')->title(__('global.address')),
            Column::computed('action')
                ->exportable(false)
                ->printable(false)
                ->width(90)
                ->addClass('text-center'),
        ];
    }

    /**
     * Get the filename for export.
     */
    protected function filename(): string
    {
        return 'Warehouses_' . date('YmdHis');
    }
}
