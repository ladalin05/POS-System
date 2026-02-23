<?php

namespace App\DataTables\Sales;

use Carbon\Carbon;
use App\Models\Sales\Sales;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Services\DataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;

class SalesDataTable extends DataTable
{
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->addColumn('action', fn($a) => view('sales.action', compact('a')))
            ->editColumn('date', function ($row) {
                if (empty($row->date)) {
                    return '';
                }
                $d = $row->date instanceof Carbon ? $row->date : Carbon::parse($row->date);
                return $d->setTimezone('Asia/Phnom_Penh')->format('Y-m-d H:i');
            })
            ->addColumn('reference_no', fn($a) => $a->reference_no ?? '')
            ->addColumn('customer', fn($a) => $a->customer->name ?? '-')
            ->addColumn('salesman', fn($a) => $a->salesman->name ?? '-')
            ->editColumn('total', fn($a) => number_format((float) ($a->total ?? 0), 2))
            ->editColumn('order_tax', fn($a) => number_format((float) ($a->order_tax ?? 0), 2))
            ->editColumn('returned', fn($a) => number_format((float) ($a->returned ?? 0), 2))
            ->editColumn('order_discount', fn($a) => number_format((float) ($a->order_discount ?? 0), 2))
            ->editColumn('grand_total', fn($a) => '<strong>' . number_format((float) ($a->grand_total ?? 0), 2) . '</strong>')
            ->editColumn('paid', fn($a) => number_format((float) ($a->paid ?? 0), 2))
            ->editColumn('balance', fn($a) => number_format((float) ($a->balance ?? 0), 2))
            ->editColumn('delivery_status', function ($row) {
                $s = (string) ($row->delivery_status ?? '');
                if ($s === '')
                    return '-';
                return '<span class="badge bg-info">' . ucfirst($s) . '</span>';
            })
            ->editColumn('payment_status', function ($row) {
                $s = strtolower((string) ($row->payment_status ?? ''));
                $color = match ($s) {
                    'paid' => 'success',
                    'partial' => 'warning',
                    'due' => 'danger',
                    default => 'secondary',
                };
                return '<span class="badge bg-' . $color . '">' . ucfirst($s) . '</span>';
            })
            ->rawColumns(['action', 'grand_total', 'delivery_status', 'payment_status'])
            ->setRowId('id');
    }

    public function query(Sales $model): QueryBuilder
    {
        // eager load relations used in columns; adjust relation names if your model uses different names
        return $model->newQuery()
            ->with([
                'customer:id,name',
                'salesman:id,name'
            ]);
    }

    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('sales-table')
            ->columns($this->getColumns())
            ->minifiedAjax()
            ->orderBy(1, 'desc') // order by Date (index 1)
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

    public function getColumns(): array
    {
        return [
            Column::make('id')->visible(false),
            Column::make('date')->title('Date')->width(150),
            Column::make('reference_no')->title('Reference No'),
            Column::make('customer')->title('Customer')->orderable(false)->searchable(true),
            Column::make('salesman')->title('Salesman')->orderable(false)->searchable(true),
            Column::make('total')->title('Total')->addClass('text-end')->searchable(false),
            Column::make('tax')->title('Tax')->addClass('text-end')->searchable(false),
            Column::make('returned')->title('Returned')->addClass('text-end')->searchable(false),
            Column::make('discount')->title('Discount')->addClass('text-end')->searchable(false),
            Column::make('grand_total')->title('Grand Total')->addClass('text-end')->searchable(false),
            Column::make('paid')->title('Paid')->addClass('text-end')->searchable(false),
            Column::make('balance')->title('Balance')->addClass('text-end')->searchable(false),
            Column::make('delivery_status')->title('Delivery Status')->orderable(false)->searchable(false),
            Column::make('payment_status')->title('Payment Status')->orderable(false)->searchable(false),
            Column::computed('action')
                ->exportable(false)->printable(false)
                ->width(90)->addClass('text-center no-modal'),
        ];
    }

    protected function filename(): string
    {
        return 'Sales_' . date('YmdHis');
    }
}
