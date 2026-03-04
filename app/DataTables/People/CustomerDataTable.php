<?php

namespace App\DataTables\People;

use App\Models\People\Customer;
use Livewire\Attributes\Title;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Services\DataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;

class CustomerDataTable extends DataTable
{
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return datatables()
            ->eloquent($query)
            ->addIndexColumn()
            ->addColumn('action', fn($row) => view('people.customer.action', compact('row')))
            ->addColumn('group_customer', fn($row) => $row->group_customer->group_name ?? '-')
            ->addColumn('deposit', function ($row) {
                return number_format($row->depositsAmount->sum('amount'), 2);
            })
            ->rawColumns(['action'])
            ->setRowId('id');
    }

    public function query(Customer $model): QueryBuilder
    {
        return $model->newQuery()->with('group_customer', 'depositsAmount');

    }

    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('customers-table')
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
            Column::make('code'),
            Column::make('company'),
            Column::make('name'),
            Column::make('phone'),
            Column::make('group_customer'),
            Column::make('email_address'),
            Column::make('deposit'),
            Column::make('city'),
            Column::computed('action')
                ->exportable(false)
                ->printable(false)
                ->width(60)
                ->addClass('text-center'),
        ];
    }

    protected function filename(): string
    {
        return 'Customers_' . date('YmdHis');
    }
}
