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
        return (new EloquentDataTable($query))
            ->addColumn('action', fn($a) => view('people.customer.action', compact('a')))
            ->addColumn('group_customer', fn($a) => $a->group_customer->group_name ?? '-')
            ->addColumn('deposit', function ($a) {
                return number_format($a->depositsAmount->sum('amount'), 2);
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
            Column::make('id')->visible(false),
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
