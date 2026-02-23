<?php

namespace App\DataTables\Expense;

use App\Models\Expense\Expense;
use App\Models\Expense\ExpenseCategory;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Services\DataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;

class ExpenseCategoryDataTable extends DataTable
{
  
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->addColumn('checkbox', function ($a) {
                return '<input type="checkbox" class="row-checkbox" value="' . $a->id . '">';
            })
            ->addColumn('action', fn($a) => view('expense.expense_category.action', compact('a')))
            ->rawColumns(['checkbox', 'action'])
            ->setRowId('id');
    }


    public function query(ExpenseCategory $model): QueryBuilder
    {
     
        return $model->newQuery();
    }

    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('expense_category-table')
            ->columns($this->getColumns())
            ->minifiedAjax()
            ->orderBy(1) // order by Code
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
     * Get the dataTable columns definition.
     */
    public function getColumns(): array
    {
        return [
            Column::computed('checkbox')
                ->exportable(false)
                ->printable(false)
                ->width(30)
                ->title('<input type="checkbox" id="select-all">')
                ->addClass('text-center'),

            Column::make('id')->title('ID')->width(60),
            Column::make('code')->title(__('global.code')),
            Column::make('name')->title(__('global.name')),
            Column::make('parent'),
            Column::computed('action')
                ->exportable(false)
                ->printable(false)
                ->width(90)
                ->addClass('text-center'),
        ];
    }

    /**
     * Get the filename for export.
     */
    protected function filename(): string
    {
        return 'expense_category_' . date('YmdHis');
    }
}
