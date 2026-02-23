<?php

namespace App\DataTables\Product;

use App\Models\Adjustment\StockMove;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Services\DataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;




class AlertQuantityDataTable extends DataTable
{

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
            ->addColumn('action', fn($a) => view('product.products.alert_qty.action', compact('a')))
            ->rawColumns(['image', 'action'])
            ->setRowId('id');
    }


    public function query(StockMove $model): QueryBuilder
    {
        return $model->newQuery()
            ->select([
                'products.id',
                'products.image',
                'products.code',
                'products.name',
                'products.type',
                'products.category_id',
                'products.unit_id',
                'products.cost',
                'products.price',
                'products.alert_quantity',
                DB::raw('COALESCE(SUM(stockmoves.unit_quantity), 0) AS qty')
            ])
            ->leftJoin('products', 'products.id', '=', 'stockmoves.product_id')
            ->groupBy(
                'products.id',
                'products.image',
                'products.code',
                'products.name',
                'products.type',
                'products.category_id',
                'products.unit_id',
                'products.cost',
                'products.price',
                'products.alert_quantity'
            )
            ->havingRaw('qty <= products.alert_quantity');
    }


    /**
     * Optional method if you want to use the html builder.
     */
    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('alert_quantity-table')
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
            Column::make('id')->title('ID')->data('id')->name('products.id'),
            Column::make('image')->title('Image')->data('image')->name('products.image')->orderable(false)->searchable(false),
            Column::make('code')->title('Code')->data('code')->name('products.code'),
            Column::make('name')->title('Name')->data('name')->name('products.name'),
            Column::make('qty')->title('Qty')->data('qty')->name('qty'),
            Column::make('alert_quantity')->title('Alert Qty')->data('alert_quantity')->name('products.alert_quantity'),

        ];
    }

    /**
     * Get the filename for export.
     */
    protected function filename(): string
    {
        return 'AlertQuantity_' . date('YmdHis');
    }
}
