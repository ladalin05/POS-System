<?php

namespace App\DataTables\Sales;

use App\Models\Sales\Invoice;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class InvoiceDataTable extends DataTable
{
    public function dataTable($query)
    {
        return (new EloquentDataTable($query))
            ->addIndexColumn()

            ->addColumn('customer_name', function ($row) {
                return optional($row->customer)->name ?? '-';
            })

            ->addColumn('warehouse_name', function ($row) {
                return optional($row->warehouse)->name ?? '-';
            })

            ->editColumn('invoice_date', function ($row) {
                return $row->invoice_date 
                    ? \Carbon\Carbon::parse($row->invoice_date)->format('Y-m-d') 
                    : '-';
            })

            ->editColumn('grand_total', function ($row) {
                return number_format((float)$row->grand_total, 2);
            })

            ->editColumn('paid_amount', function ($row) {
                return number_format((float)$row->paid_amount, 2);
            })

            ->editColumn('due_amount', function ($row) {
                return number_format((float)$row->due_amount, 2);
            })
            ->addColumn('action', function ($row) {
                return view('sales.invoices.action', compact('row'));
            })
            ->rawColumns(['action']);
    }

    public function query(Invoice $model)
    {
        return $model->newQuery()
            ->with(['customer', 'warehouse'])
            ->select('invoices.*'); // IMPORTANT to avoid ambiguous column errors
    }

    public function html()
    {
        return $this->builder()
            ->setTableId('invoice-table')
            ->columns($this->getColumns())
            ->minifiedAjax()
            ->orderBy(0, 'desc');
    }

    protected function getColumns()
    {
        return [
            Column::make('id'),
            Column::make('invoice_no'),
            Column::make('customer_name')->title('Customer')->orderable(false)->searchable(false),
            Column::make('warehouse_name')->title('Warehouse')->orderable(false)->searchable(false),
            Column::make('grand_total'),
            Column::make('paid_amount'),
            Column::make('due_amount'),
            Column::make('status'),
            Column::make('invoice_date'),
            Column::computed('action')
                ->exportable(false)
                ->printable(false)
                ->width(120)
                ->addClass('text-center'),
        ];
    }

    protected function filename(): string
    {
        return 'Invoices_' . date('YmdHis');
    }
}