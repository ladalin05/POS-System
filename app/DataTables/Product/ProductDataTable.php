<?php

namespace App\DataTables\Product;

use App\Models\Product\Product;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Services\DataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;

class ProductDataTable extends DataTable
{

    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return datatables()->eloquent($query)
        ->addIndexColumn()
        ->addColumn('category_name', fn($row) => $row->category_name ?? '-')
        ->editColumn('price', function ($row) {
            return '$ ' . number_format($row->price, 2);
        })
        ->editColumn('barcode', function ($row) {
            if (!$row->barcode) return '-';
            $barcode = app(\Milon\Barcode\DNS1D::class);
            return '<img src="data:image/png;base64,' .
                $barcode->getBarcodePNG($row->barcode, 'C128') .
            '" />';
        })
        ->editColumn('selling_type', function ($row) {
            $class = $row->selling_type === 'Retail' ? 'bg-success' : 'bg-info';
            return '<span class="badge ' . $class . '">' . ucfirst($row->selling_type) . '</span>';
        })
        ->addColumn('action', function ($row) {
            return view('product.products.action', compact('row'));
        })
        ->rawColumns(['action', 'price', 'barcode', 'selling_type'])
        ->setRowId('id');
    }


    /**
     * Query Source
     */
    public function query(Product $model): QueryBuilder
    {

        return $model->newQuery()
            ->leftJoin('categories as c', 'products.category_id', '=', 'c.id')
            ->leftJoin('categories as sc', 'products.sub_category_id', '=', 'sc.id')
            ->leftJoin('brands', 'products.brand_id', '=', 'brands.id')
            ->leftJoin('units', 'products.unit_id', '=', 'units.id')
            ->leftJoin('manufactures', 'products.manufacture_id', '=', 'manufactures.id')
            ->select(
                'products.*',
                'c.name as category_name',
                'sc.name as sub_category_name',
                'brands.name as brand_name',
                'units.name as unit_name',
                'manufactures.name as manufacture_name'
            );
    }


    /**
     * HTML Builder
     */
    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('product-table')
            ->columns($this->getColumns())
            ->minifiedAjax()
            ->orderBy(2)
            ->selectStyleSingle()
            ->buttons([
                Button::make('excel'),
                Button::make('csv'),
                Button::make('pdf'),
                Button::make('print'),
                Button::make('reset'),
                Button::make('reload')
            ]);
    }


    /**
     * Columns
     */
    public function getColumns(): array
    {
        return [

            Column::computed('DT_RowIndex')
                ->title('No')
                ->width(60)
                ->addClass('text-center'),
            Column::make('product_name')->title('Product'),
            Column::make('sku')->title('SKU'),
            Column::make('selling_type')->title('Selling'),
            Column::make('category_name')->title('Category'),
            Column::make('sub_category_name')->title('Sub Category')->addClass('text-nowrap'),
            Column::make('brand_name')->title('Brand'),
            Column::make('unit_name')->title('Unit'),
            Column::make('price')->title('Price')->addClass('text-end text-nowrap'),
            Column::make('barcode')->title('Barcode')->addClass('text-center'),
            Column::computed('action')
                ->exportable(false)
                ->printable(false)
                ->width(80)
                ->addClass('text-center no-modal'),

        ];
    }


    /**
     * Export file name
     */
    protected function filename(): string
    {
        return 'Products_' . date('YmdHis');
    }

}