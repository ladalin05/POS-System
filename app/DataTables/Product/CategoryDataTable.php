<?php

namespace App\DataTables\Product;
use App\Models\Product\Category;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Services\DataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;

class CategoryDataTable extends DataTable
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
            ->addColumn('action', fn ($row) => view('product.categories.action', compact('row')))
            ->addColumn('api', fn($row) => clipboard(url("api/v1/{$row->endpoint}")))
            ->addColumn('endpoint', fn($row) => clipboard("{$row->url}"))
            ->rawColumns(['action', 'api', 'endpoint', 'image'])
            ->setRowId('id');
    }

    /**
     * Get the query source of dataTable.
     */
    public function query(Category $model): QueryBuilder
    {
        return $model->newQuery();
    }

    /**
     * Optional method if you want to use the html builder.
     */
    public function html(): HtmlBuilder
    {
        return $this->builder()
                    ->setTableId('categories-table')
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
            Column::make('parent_id')
                  ->title(__('Parent Category'))
                  ->searchable(false)
                  ->orderable(false),
            Column::computed('action')
                  ->exportable(false)
                  ->printable(false)
                  ->width(60)
                  ->addClass('text-center'),
        ];
    }

    /**
     * Get the filename for export.
     */
    protected function filename(): string
    {
        return 'Categories_' . date('YmdHis');
    }
}
