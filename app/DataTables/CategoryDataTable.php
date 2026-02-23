<?php

namespace App\DataTables;

use App\Models\Cotegories;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class CategoryDataTable extends DataTable
{
    /**
     * Build DataTable
     */
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return datatables()
            ->eloquent($query)
            ->addIndexColumn()
            ->editColumn('name', function ($category) {
                return '<span class="fw-semibold">'.$category->name.'</span>';
            })
            ->addColumn('parent', function ($category) {
                return $category->parent
                    ? '<span class="text-muted">'.$category->parent->name.'</span>'
                    : '<span class="badge bg-secondary">Main</span>';
            })
            ->addColumn('status', function ($category) {
                return $category->status === 'active'
                    ? '<span class="status-badge-active"><span class="dot"></span> Active</span>'
                    : '<span class="status-badge-danger"><span class="dot"></span> Inactive</span>';
            })
            ->addColumn('action', function ($category) {
                return view('categories.action', compact('category'));
            })
            ->rawColumns([
                'name',
                'parent',
                'status',
                'action',
            ])

            ->setRowId('id');
    }

    /**
     * Query source
     */
    public function query(Cotegories $model): QueryBuilder
    {
        return $model->newQuery()
            ->select([
                'id',
                'name',
                'slug',
                'parent_id',
                'description',
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

            Column::make('name')
                ->title(__('Category Name')),

            Column::make('slug')
                ->title(__('Slug')),

            Column::computed('parent')
                ->title(__('Parent Category'))
                ->orderable(false)
                ->searchable(false),

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
