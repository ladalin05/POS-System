<?php

namespace App\DataTables\Expense;
use Carbon\Carbon;
use App\Models\Adjustment\Adjustment;
use App\Models\Expense\Expense;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Services\DataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;

class ExpenseDataTable extends DataTable
{
    /**
     * Build the DataTable class.
     *
     * @param QueryBuilder $query Results from query() method.
     */
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->addColumn('checkbox', fn($a) => '<input type="checkbox" class="row-checkbox" value="' . $a->id . '">')
            ->addColumn('paid_by', fn($a) => $a->cashAccount->name )
            ->addColumn('branch',fn($a) => $a->branch->name)
            ->addColumn('action', fn($a) => view('expense.add_expense.action', compact('a')))
            ->editColumn('date', function ($row) {
                if (empty($row->date)) {
                    return '';
                }
                $d = $row->date instanceof Carbon ? $row->date : Carbon::parse($row->date);
                return $d->setTimezone('Asia/Phnom_Penh')->format('Y-m-d H:i');
            })
            ->addColumn('warehouse', fn($a) => $a->warehouse->name ?? '-')
            ->rawColumns(['action', 'checkbox'])
            ->setRowId('id');
    }

    /**
     * Get the query source of dataTable.
     */
    public function query(Expense $model): QueryBuilder
    {
        return $model->newQuery()->with('branch','cashAccount');
    }

    /**
     * Optional method if you want to use the html builder.
     */
    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('expenses-table')
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
            Column::computed('checkbox')
                ->exportable(false)
                ->printable(false)
                ->width(30)
                ->title('<input type="checkbox" id="select-all">')
                ->addClass('text-center'),
            Column::make('id'),
            Column::make('date')->title(__('global.date'))->width(200),
            Column::make('reference_no')->title(__('global.reference_no'))->width(140),
            Column::make('branch')->title(__('global.branch')),
            Column::make('warehouse')->title(__('global.warehouse')),
            Column::make('paid_by')->title(__('global.paying_by'))->orderable(false),
            Column::make('grand_total')->title(__('global.total'))->addClass('text-end'),

            Column::computed('action')
                ->exportable(false)
                ->printable(false)
                ->width(110)
                ->addClass('text-center no-modal'),
        ];
    }

    /**
     * Get the filename for export.
     */
    protected function filename(): string
    {
        return 'Expense_' . date('YmdHis');
    }
}
