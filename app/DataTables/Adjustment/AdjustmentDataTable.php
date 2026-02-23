<?php

namespace App\DataTables\Adjustment;
use Carbon\Carbon;
use App\Models\Adjustment\Adjustment;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Services\DataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;

class AdjustmentDataTable extends DataTable
{
    /**
     * Build the DataTable class.
     *
     * @param QueryBuilder $query Results from query() method.
     */
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->addColumn('action', fn($a) => view('adjustment.action', compact('a')))
            ->editColumn('date', function ($row) {
                if (empty($row->date)) {
                    return '';
                }
                $d = $row->date instanceof Carbon ? $row->date : Carbon::parse($row->date);
                return $d->setTimezone('Asia/Phnom_Penh')->format('Y-m-d H:i');
            })
            ->addColumn('warehouse', fn($a) => $a->warehouse->name ?? '-')


            ->editColumn('status', function ($row) {
                $status = strtolower($row->status);
                $color = match ($status) {
                    'pending' => 'warning',
                    'approved' => 'success',
                    default => 'secondary',
                };
                return '<span class="badge bg-' . $color . '">' . ucfirst($status) . '</span>';
            })
            ->rawColumns(['action', 'status'])
            ->setRowId('id');
    }

    /**
     * Get the query source of dataTable.
     */
    public function query(Adjustment $model): QueryBuilder
    {
        return $model->newQuery();
    }

    /**
     * Optional method if you want to use the html builder.
     */
    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('adjustment-table')
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
            Column::make('date'),
            Column::make('reference_no')
                ->title('Reference No'),
            Column::make('warehouse')
                ->title('Warehouse'),
            Column::make('note')
                ->title('Note'),
            Column::make('status'),
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
        return 'Adjustment_' . date('YmdHis');
    }
}
