<?php

namespace App\DataTables;

use App\Models\Products;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class ProductDataTable extends DataTable
{
    /**
     * Build DataTable
     */
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return datatables()
            ->eloquent($query)
            ->addIndexColumn()

            ->editColumn('product_name', function ($product) {
                return '<span class="fw-semibold">'.$product->product_name.'</span>';
            })

            ->editColumn('selling_type', function ($product) {
                return $product->selling_type === 'Retail'
                    ? '<span class="badge bg-primary">Retail</span>'
                    : '<span class="badge bg-info">Wholesale</span>';
            })

            ->editColumn('price', function ($product) {
                return '$'.number_format($product->price, 2);
            })

            ->editColumn('quantity', function ($product) {
                return $product->quantity <= $product->quantity_alert
                    ? '<span class="text-danger fw-bold">'.$product->quantity.'</span>'
                    : $product->quantity;
            })

            ->addColumn('status', function ($product) {
                return $product->quantity <= $product->quantity_alert
                    ? '<span class="status-badge-danger"><span class="dot"></span> Low Stock</span>'
                    : '<span class="status-badge-active"><span class="dot"></span> Available</span>';
            })

            ->addColumn('action', function ($product) {
                return view('products.action', compact('product'));
            })

            ->rawColumns([
                'product_name',
                'selling_type',
                'price',
                'quantity',
                'status',
                'action',
            ])

            ->setRowId('id');
    }

    /**
     * Query source
     */
    public function query(Products $model): QueryBuilder
    {
        $model = $model->join('categories', 'categories.id', '=', 'products.category_id')
            ->select([
                'products.id',
                'products.product_name',
                'categories.name as category',
                'products.sku',
                'products.selling_type',
                'products.price',
                'products.quantity',
                'products.quantity_alert',
                'products.created_at',
            ]);
        return $model;
    }

    /**
     * HTML builder
     */
    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('product-table')
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

            Column::make('product_name')
                ->title(__('Product Name')),

            Column::make('category')
                ->title(__('Category')),

            Column::make('sku')
                ->title(__('SKU')),

            Column::make('selling_type')
                ->title(__('Type'))
                ->orderable(false),

            Column::make('price')
                ->title(__('Price')),

            Column::make('quantity')
                ->title(__('Qty')),

            Column::computed('status')
                ->title(__('Status'))
                ->orderable(false)
                ->searchable(false),

            Column::computed('action')
                ->title('')
                ->width(120)
                ->orderable(false)
                ->searchable(false),
        ];
    }
}
