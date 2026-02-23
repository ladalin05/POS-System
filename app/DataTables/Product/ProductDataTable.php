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
        return (new EloquentDataTable($query))
            ->addColumn('image', function ($a) {
                // clean image path
                $path = ltrim($a->image ?? '', '/');

                // if empty OR file does not exist → use default
                if (!$path || !file_exists(public_path($path))) {
                    $path = 'assets/images/no_image.png';
                }

                return '<img src="' . asset($path) . '" class="img-thumbnail" style="width:35px; height:35px;" />';
            })
            ->addColumn('category_name', fn($a) => $a->category->name ?? '-')
            ->addColumn('action', fn($a) => view('product.products.action', compact('a')))
            ->rawColumns(['image', 'action'])
            ->setRowId('id');
    }


    /**
     * Get the query source of dataTable.
     */
    public function query(Product $model): QueryBuilder
    {
        return $model->newQuery()->select([
            'id',
            'image',
            'code',
            'name',
            'type',
            'category_id',
            'unit_id',
            'cost',
            'price',
            'alert_quantity'
        ])->with('category', 'unit');


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
            Column::make('id'),
            Column::make('image'),
            Column::make('code'),
            Column::make('name'),
            Column::make('type'),
            Column::make('category_name')->title('Category'),
            Column::make('unit_id'),
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
