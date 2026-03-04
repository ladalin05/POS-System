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
        return datatables()
            ->eloquent($query)
            ->addIndexColumn()
            ->addColumn('action', fn($row) => view('sales.action', compact('row')))
            ->editColumn('date', function ($row) {
                if (empty($row->date)) {
                    return '';
                }
                $d = $row->date instanceof Carbon ? $row->date : Carbon::parse($row->date);
                return $d->setTimezone('Asia/Phnom_Penh')->format('Y-m-d H:i');
            })
            ->addColumn('reference_no', fn($row) => $row->reference_no ?? '')
            ->addColumn('customer_name', fn($row) => $row->customer_name ?? '-')
            ->addColumn('biller_name', fn($row) => $row->{'biller_name_' . app()->getLocale()} ?? '-')
            ->editColumn('total', fn($row) => number_format((float) ($row->total ?? 0), 2))
            ->editColumn('order_tax', fn($row) => number_format((float) ($row->order_tax ?? 0), 2))
            ->editColumn('returned', fn($row) => number_format((float) ($row->returned ?? 0), 2))
            ->editColumn('order_discount', fn($row) => number_format((float) ($row->order_discount ?? 0), 2))
            ->editColumn('grand_total', fn($row) => '<strong>' . number_format((float) ($row->grand_total ?? 0), 2) . '</strong>')
            ->editColumn('paid', fn($row) => number_format((float) ($row->paid ?? 0), 2))
            ->editColumn('balance', fn($row) => number_format((float) ($row->balance ?? 0), 2))
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
        $model = $model->newQuery()
                ->join('customers', 'sales.customer_id', '=', 'customers.id')
                ->join('users as biller', 'sales.biller_id', '=', 'biller.id')
                ->select('sales.*', 'customers.name as customer_name', 'biller.name_en as biller_name_en', 'biller.name_kh as biller_name_kh');
        return $model;
    }

    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('user-table')
            ->columns($this->getColumns())
            ->minifiedAjax()
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

    public function getColumns(): array
    {
        return [
            Column::computed('DT_RowIndex')
                ->title(__('global.n_o'))
                ->width(60)
                ->addClass('text-center'),
            Column::make('date')->title('Date')->width(150)->addClass('text-nowrap'),
            Column::make('reference_no')->title('Reference No')->addClass('text-nowrap'),
            Column::make('customer_name')->title('Customer')->orderable(false)->searchable(true)->addClass('text-nowrap'),
            Column::make('biller_name')->title('Biller')->orderable(false)->searchable(true),
            Column::make('total')->title('Total')->addClass('text-end')->searchable(false),
            Column::make('tax')->title('Tax')->addClass('text-end')->searchable(false),
            Column::make('returned')->title('Returned')->addClass('text-end')->searchable(false),
            Column::make('discount')->title('Discount')->addClass('text-end')->searchable(false),
            Column::make('grand_total')->title('Grand Total')->addClass('text-end')->searchable(false)->addClass('text-nowrap'),
            Column::make('paid')->title('Paid')->addClass('text-end')->searchable(false),
            Column::make('balance')->title('Balance')->addClass('text-end')->searchable(false),
            Column::make('delivery_status')->title('Delivery Status')->orderable(false)->searchable(false)->addClass('text-nowrap'),
            Column::make('payment_status')->title('Payment Status')->orderable(false)->searchable(false)->addClass('text-nowrap'),
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
