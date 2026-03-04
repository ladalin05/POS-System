<?php

namespace App\DataTables\Setting;
use App\Models\Setting\UnitConvert;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Services\DataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;

class UnitConvertDataTable extends DataTable
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
            ->editColumn('unit_from_name', fn($row) => $row->unit_from_name)
            ->editColumn('unit_to_name', fn($row) => $row->unit_to_name)
            ->addColumn('action', fn($row) => view('setting.unit_convert.action', compact('row')))
            ->rawColumns(['checkbox', 'action'])
            ->setRowId('id');
    }

    /**
     * Get the query source of dataTable.
     */
    public function query(UnitConvert $model): QueryBuilder
    {

        return $model->newQuery()
            ->leftJoin('units as u_from', 'u_from.id', '=', 'unit_converts.unit_from_id')
            ->leftJoin('units as u_to', 'u_to.id', '=', 'unit_converts.unit_to_id')
            ->select([
                'unit_converts.*',
                'u_from.name as unit_from_name',
                'u_to.name as unit_to_name',
            ]);
    }

    /**
     * Optional method if you want to use the html builder.
     */
    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('unitconvert-table')
            ->columns($this->getColumns())
            ->minifiedAjax()
            //->dom('Bfrtip')
            ->orderBy(1)
            ->selectStyleSingle()
            ->buttons([
                Button::make('excel'),
                Button::make('csv'),
                Button::make('pdf'),
                Button::make('print'),
                Button::make('reset'),
                Button::make('reload')
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
            Column::make('unit_from_name')->title(__('global.unit_from')),
            Column::make('unit_to_name')->title(__('global.unit_to')),
            Column::make('operator'),
            Column::computed('action')
                ->exportable(false)
                ->printable(false)
                ->width(60)
                ->addClass('text-center'),
        ];
    }

    /**
     * Get the filename for export.
     */
    protected function filename(): string
    {
        return 'UnitConvert_' . date('YmdHis');
    }
}
