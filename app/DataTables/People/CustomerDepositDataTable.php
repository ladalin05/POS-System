<?php

namespace App\DataTables\People;

use App\Models\People\CustomerDeposit;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Services\DataTable;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\Html\Builder as HtmlBuilder;

class CustomerDepositDataTable extends DataTable
{
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->addColumn('action', fn($a) => view('people.customer_deposit.action', compact('a')))
            ->addColumn('customer', fn($a) => $a->customer_name->name ?? '-')
            ->addColumn('branch',   fn($a) => $a->branch->name ?? '-')
            ->addColumn('paid_by',  fn($a) => $a->paying_by->name ?? '-')
           
            ->rawColumns(['action'])
            ->setRowId('id');
    }

    public function query(CustomerDeposit $model): QueryBuilder
    {
        return $model->newQuery()->with(['customer_name','branch','paying_by']);
    }

    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('customers_deposit-table')
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
            Column::make('id')->visible(false),
            Column::make('reference_no')->title(__('global.reference_no')),
            Column::make('customer')->title(__('global.customer')),
            Column::make('branch')->title(__('global.branch')),
            Column::make('amount')->title(__('global.amount')),
            Column::make('paid_by')->title(__('global.paid_by')),
            Column::make('note')->title(__('global.note')),
            Column::computed('action')
                ->exportable(false)
                ->printable(false)
                ->width(60)
                ->addClass('text-center'),
        ];
    }

    protected function filename(): string
    {
        return 'CustomersDeposit_' . date('YmdHis');
    }
}
