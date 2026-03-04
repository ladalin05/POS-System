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
    /**
     * Build the DataTable class.
     *
     * @param QueryBuilder $query Results from query() method.
     */
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return datatables()
            ->eloquent($query)
            ->addIndexColumn()
            ->addColumn('image', function ($row) {
                $path = ltrim($row->image ?? '', '/');
                if (!$path || !file_exists(public_path($path))) {
                    $path = 'assets/images/no_image.png';
                }
                return '<img src="' . asset($path) . '" class="img-thumbnail" style="width:35px; height:35px;" />';
            })
            ->addColumn('category_name', fn($row) => $row->category_name ?? '-')
            ->addColumn('unit_name', fn($row) => $row->unit_name ?? '-')
            ->addColumn('action', fn($row) => view('product.products.action', compact('row')))
            ->rawColumns(['image', 'action'])
            ->setRowId('id');
    }


    /**
     * Get the query source of dataTable.
     */
    public function query(Product $model): QueryBuilder
    {
        return $model->newQuery()
                ->leftJoin('categories', 'products.category_id', '=', 'categories.id')
                ->leftJoin('units', 'products.unit_id', '=', 'units.id')
                ->select('products.*', 'categories.name as category_name', 'units.name as unit_name');


    }

    /**
     * Optional method if you want to use the html builder.
     */
    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('product-table')
            ->columns($this->getColumns())
            ->minifiedAjax()
            //->dom('Bfrtip')
            ->orderBy(1)
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
     * Get the dataTable columns definition.
     */
    public function getColumns(): array
    {
        return [
            Column::computed('DT_RowIndex')
                ->title(__('global.n_o'))
                ->width(60)
                ->addClass('text-center'),
            Column::make('image'),
            Column::make('code'),
            Column::make('name'),
            Column::make('type'),
            Column::make('category_name')->title('Category'),
            Column::make('unit_name')->title('Unit'),
            Column::make('cost'),
            Column::make('price'),
            Column::make('alert_quantity'),
            Column::computed('action')
                ->exportable(false)
                ->printable(false)
                ->width(60)
                ->addClass('text-center no-modal'),
        ];
    }

    /**
     * Get the filename for export.
     */
    protected function filename(): string
    {
        return 'Product_' . date('YmdHis');
    }
}
