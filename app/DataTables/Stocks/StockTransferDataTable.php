<?php

namespace App\DataTables\Stocks;

use App\Models\Stocks\StockMove;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Services\DataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;

class StockTransferDataTable extends DataTable
{
    /* ==========================================
       DATA TABLE
    ===========================================*/

    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return datatables()
            ->eloquent($query)
            ->addIndexColumn()

            ->editColumn('date', function ($row) {
                return $row->date ? $row->date->format('Y-m-d H:i') : '-';
            })

            ->editColumn('quantity', function ($row) {
                return number_format($row->quantity, 2);
            })

            ->addColumn('product_name', function ($row) {
                return $row->product_name ?? '-';
            })

            ->addColumn('warehouse_name', function ($row) {
                return $row->warehouse_name ?? '-';
            })

            ->addColumn('user_name', function ($row) {
                return $row->{'user_name_' . app()->getLocale()} ?? '-';
            })

            ->addColumn('action', function ($row) {
                return view('stocks.stock_transfer.action', compact('row'))->render();
            })

            ->rawColumns(['action'])
            ->setRowId('id');
    }

    /* ==========================================
       QUERY
    ===========================================*/

    public function query(StockMove $model): QueryBuilder
    {
        $model = $model->newQuery()
                ->join('products', 'products.id', '=', 'stockmoves.product_id')
                ->join('warehouses', 'warehouses.id', '=', 'stockmoves.warehouse_id')
                ->join('users', 'users.id', '=', 'stockmoves.created_by')
                ->select([
                    'stockmoves.*',
                    'products.name as product_name',
                    'warehouses.name as warehouse_name',
                    'users.name_en as user_name_en',
                    'users.name_kh as user_name_kh',
                ])
                ->orderBy('id', 'desc');

        return $model;
    }

    /* ==========================================
       HTML BUILDER
    ===========================================*/

    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('stockmove-table')
            ->columns($this->getColumns())
            ->minifiedAjax()
            ->orderBy(0)
            ->buttons([
                Button::make('excel'),
                Button::make('csv'),
                Button::make('pdf'),
                Button::make('print'),
                Button::make('reset'),
                Button::make('reload'),
            ]);
    }

    /* ==========================================
       COLUMNS
    ===========================================*/

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

            Column::make('transaction'),

            Column::make('reference_no'),

            Column::make('quantity'),

            Column::make('date'),

            Column::computed('user_name')
                ->title('Created By'),

            Column::computed('action')
                ->exportable(false)
                ->printable(false)
                ->width(80)
                ->addClass('text-center'),
        ];
    }

    /* ==========================================
       FILE NAME
    ===========================================*/

    protected function filename(): string
    {
        return 'StockTransfer_' . date('YmdHis');
    }
}