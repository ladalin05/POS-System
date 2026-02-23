<?php

namespace App\DataTables;

use App\Models\Suppliers;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class SupplierDataTable extends DataTable
{
    /**
     * Build DataTable
     */
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return datatables()
            ->eloquent($query)
            ->addIndexColumn()

            ->editColumn('company_name', function ($supplier) {
                return '<span class="fw-semibold">'.$supplier->company_name.'</span>';
            })

            ->editColumn('email', function ($supplier) {
                return '<a href="mailto:'.$supplier->email.'">'.$supplier->email.'</a>';
            })

            ->editColumn('status', function ($supplier) {
                return $supplier->status === 'active'
                    ? '<span class="badge bg-success">Active</span>'
                    : '<span class="badge bg-danger">Inactive</span>';
            })

            ->addColumn('action', function ($supplier) {
                return view('suppliers.action', compact('supplier'));
            })

            ->rawColumns([
                'company_name',
                'email',
                'status',
                'action',
            ])

            ->setRowId('id');
    }

    /**
     * Query source
     */
    public function query(Suppliers $model): QueryBuilder
    {
        return $model->newQuery()->select([
            'id',
            'company_name',
            'first_name',
            'last_name',
            'email',
            'phone',
            'city',
            'country',
            'status',
            'created_at',
        ]);
    }

    /**
     * HTML builder
     */
    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('supplier-table')
            ->columns($this->getColumns())
            ->minifiedAjax()
            ->orderBy(1)
            ->parameters([
                'responsive' => true,
                'autoWidth'  => false,
            ]);
    }

    /**
     * Columns
     */
    protected function getColumns(): array
    {
        return [
            Column::computed('DT_RowIndex')
                ->title(__('No'))
                ->width(60)
                ->addClass('text-center'),

            Column::make('company_name')
                ->title(__('Company Name')),

            Column::make('first_name')
                ->title(__('First Name')),

            Column::make('last_name')
                ->title(__('Last Name')),

            Column::make('email')
                ->title(__('Email')),

            Column::make('phone')
                ->title(__('Phone')),

            Column::make('city')
                ->title(__('City')),

            Column::make('country')
                ->title(__('Country')),

            Column::computed('status')
                ->title(__('Status'))
                ->orderable(false),

            Column::computed('action')
                ->title('')
                ->width(120)
                ->orderable(false)
                ->searchable(false),
        ];
    }
}
