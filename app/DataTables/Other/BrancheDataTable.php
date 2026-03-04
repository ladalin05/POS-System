<?php

namespace App\DataTables\Other;

use App\Models\Other\Branch;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Services\DataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;

class BrancheDataTable extends DataTable
{
    /**
     * Build the DataTable class.
     *
     * @param QueryBuilder $query Results from query() method.
     */
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return datatables()
            ->eloquent($query)
            ->addIndexColumn()
            ->addColumn('checkbox', function ($row) {
                return '<input type="checkbox" class="row-checkbox" value="' . $row->id . '">';
            })
            ->addColumn('image', function ($row) {
                return $row->logo
                    ? '<img src="' . asset($row->logo) . '" class="img-thumbnail" style="width:50px; height:50px;" />'
                    : '<span class="text-muted">No image</span>';
            })
            ->addColumn('action', fn($row) => view('other.branch.action', compact('row')))
            ->rawColumns(['checkbox', 'action', 'logo'])
            ->setRowId('id');
    }

    /**
     * Get the query source of dataTable.
     */
    public function query(Branch $model): QueryBuilder
    {
        // Select only columns we show to keep payload light
        return $model->newQuery();
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
            Column::computed('DT_RowIndex')
                ->title(__('No'))
                ->width(60)
                ->addClass('text-center'),
            Column::make('name')->title(__('global.name')),
            Column::make('city')->title(__('global.city')),
            Column::make('phone')->title(__('global.phone')),
            Column::make('email')->title(__('global.email_address')),
            Column::make('default_cash')->title(__('global.default_cash')),
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
        return 'Branches_' . date('YmdHis');
    }
}
