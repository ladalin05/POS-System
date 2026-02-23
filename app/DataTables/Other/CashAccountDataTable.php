<?php

namespace App\DataTables\Other;

use App\Models\Other\CashAccount;
use Yajra\DataTables\Services\DataTable;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Builder as HtmlBuilder;


class CashAccountDataTable extends DataTable
{
    public function dataTable($query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->addColumn('action', fn($a) => view('other.cash_accounts.action', compact('a')))
            ->rawColumns(['action']);
    }

    public function query(CashAccount $model): QueryBuilder
    {
        return $model->newQuery();

    }

    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('cash_account-table')
            ->columns($this->getColumns())
            ->minifiedAjax()
            ->orderBy(0)
            ->selectStyleSingle();
    }

    public function getColumns(): array
    {
        return [
            Column::make('id'),
            Column::make('code'),
            Column::make('name'),
            Column::make('type'),
            Column::computed('action')
                ->exportable(false)
                ->printable(false)
                ->width(60)
                ->addClass('text-center no-modal'),
        ];
    }

    protected function filename(): string
    {
        return 'CashAccount_' . date('YmdHis');
    }
}
