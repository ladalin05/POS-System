<?php

namespace App\DataTables\Stocks;

use App\Models\Stocks\ProductStock;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Services\DataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;

class ProductStockDataTable extends DataTable
{
    /**
     * Build DataTable
     */
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return datatables()
            ->eloquent($query)
            ->addIndexColumn()

            ->editColumn('product_name', function ($row) {
                return $row->product_name ?? '-';
            })

            ->editColumn('warehouse_name', function ($row) {
                return $row->warehouse_name ?? '-';
            })

            ->editColumn('stock', function ($row) {
                return number_format($row->stock, 0);
            })

            ->editColumn('alert_quantity', function ($row) {
                return number_format($row->alert_quantity, 0);
            })

            ->addColumn('status', function ($row) {
                if ($row->stock <= $row->alert_quantity) {
                    return '<span class="badge bg-danger">Low Stock</span>';
                }

                return '<span class="badge bg-success">In Stock</span>';
            })

            ->addColumn('action', function ($row) {
                return view('stocks.product_stock.action', compact('row'))->render();
            })

            ->rawColumns(['status', 'action'])
            ->setRowId('id');
    }

    /**
     * Query Source
     */
    public function query(ProductStock $model): QueryBuilder
    {
        // ✅ REMOVE dd() — THIS WAS BREAKING AJAX

        return $model->newQuery()
            ->join('products', 'product_stock.product_id', '=', 'products.id')
            ->join('warehouses', 'product_stock.warehouse_id', '=', 'warehouses.id')
            ->select([
                'product_stock.id',
                'product_stock.product_id',
                'product_stock.warehouse_id',
                'product_stock.stock',
                'product_stock.alert_quantity',
                'products.product_name as product_name',
                'warehouses.name as warehouse_name',
            ])
            ->orderBy('product_stock.id', 'desc');
    }

    /**
     * HTML Builder
     */
    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('product-stock-table')
            ->columns($this->getColumns())
            ->minifiedAjax()
            ->orderBy(0)
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
     * Columns
     */
    public function getColumns(): array
    {
        return [
            Column::computed('DT_RowIndex')
                ->title(__('global.n_o'))
                ->width(60)
                ->addClass('text-center'),

            Column::computed('product_name')
                ->title('Product'),

            Column::computed('warehouse_name')
                ->title('Warehouse'),

            Column::make('stock')
                ->title('Current Stock'),

            Column::make('alert_quantity')
                ->title('Alert Qty'),

            Column::computed('status'),

            Column::computed('action')
                ->exportable(false)
                ->printable(false)
                ->width(80)
                ->addClass('text-center no-modal'),
        ];
    }

    /**
     * Filename
     */
    protected function filename(): string
    {
        return 'ProductStock_' . date('YmdHis');
    }
}