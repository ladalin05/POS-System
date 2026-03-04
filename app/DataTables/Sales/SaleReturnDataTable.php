<?php

namespace App\DataTables\Sales;

use App\Models\Sales\SaleReturn;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class SaleReturnDataTable extends DataTable
{
    public function dataTable($query)
    {
        return (new EloquentDataTable($query))
            ->addIndexColumn()
            ->editColumn('date', function ($row) {
                return optional($row->date)->format('Y-m-d');
            })
            ->addColumn('customer_name', function ($row) {
                return $row->customer->name ?? '-';
            })
            ->addColumn('action', function ($row) {
                return view('sales.sale_returns.action', compact('row'));
            })
            ->rawColumns(['action']);
    }

    public function query(SaleReturn $model)
    {
        $model = $model->newQuery()
                ->join('customers', 'sale_returns.customer_id', '=', 'customers.id')
                ->select('sale_returns.*', 'customers.name as customer_name');
        return $model;
    }

    public function html()
    {
        return $this->builder()
            ->setTableId('sale-return-table')
            ->columns($this->getColumns())
            ->minifiedAjax()
            ->orderBy(0);
    }

    protected function getColumns()
    {
        return [
            Column::make('DT_RowIndex')
                ->title('#')
                ->searchable(false)
                ->orderable(false),

            Column::make('date'),
            Column::make('reference_no')->title('Reference No'),
            Column::make('customer_name')->title('Customer'),
            Column::make('grand_total'),
            Column::make('paid'),
            Column::make('balance'),
            Column::make('action')
                ->exportable(false)
                ->printable(false)
                ->width(120)
                ->addClass('text-center'),
        ];
    }

    protected function filename(): string
    {
        return 'SaleReturns_' . date('YmdHis');
    }
}