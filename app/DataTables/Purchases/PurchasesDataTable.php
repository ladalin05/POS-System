<?php

namespace App\DataTables\Purchases;

use Carbon\Carbon;
use App\Models\Purchase;
use App\Models\Purchases\Purchases;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Services\DataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;

class PurchasesDataTable extends DataTable
{
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->addColumn('action', fn($a) => view('purchases.action', compact('a')))
            ->editColumn('date', function ($row) {
                if (empty($row->date))
                    return '';
                $d = $row->date instanceof Carbon ? $row->date : Carbon::parse($row->date);
                return $d->setTimezone('Asia/Phnom_Penh')->format('Y-m-d H:i');
            })
            ->editColumn('status', function ($row) {
                $status = strtolower((string) $row->status);
                $color = match ($status) {
                    'pending' => 'warning',
                    'approved' => 'success',
                    default => 'secondary',
                };
                return '<span class="badge bg-' . $color . '">' . ucfirst($status) . '</span>';
            })
            ->addColumn('supplier', fn($a) => $a->supplier->name ?? '-')
            ->addColumn('branch', fn($a) => $a->branch->name ?? '-')
            ->addColumn('warehouse', fn($a) => $a->warehouse->name ?? '-')
            ->editColumn('total', fn($a) => number_format((float) $a->total, 2))
            ->editColumn('order_tax', fn($a) => number_format((float) $a->order_tax, 2))
            ->editColumn('order_discount', fn($a) => number_format((float) $a->order_discount, 2))
            ->editColumn('grand_total', fn($a) => '<strong>' . number_format((float) $a->grand_total, 2) . '</strong>')
            ->rawColumns(['action', 'grand_total','status'])
            ->setRowId('id');
    }

    public function query(Purchases $model): QueryBuilder
    {

        return $model->newQuery()
            ->with(['supplier:id,name', 'branch:id,name', 'warehouse:id,name']);
    }

    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('purchases-table')
            ->columns($this->getColumns())
            ->minifiedAjax()
            ->orderBy(1, 'desc')
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
            Column::make('id'),
            Column::make('date')->title('Date')->width(150),
            Column::make('reference_no')->title('Reference No'),
            Column::make('supplier')->title('Supplier')->orderable(false)->searchable(false),
            Column::make('branch')->title('Branch')->orderable(false)->searchable(false),
            Column::make('warehouse')->title('Warehouse')->orderable(false)->searchable(false),
            Column::make('total')->title('Total (USD)')->addClass('text-end')->searchable(false),
            Column::make('order_tax')->title('Order Tax')->addClass('text-end')->searchable(false),
            Column::make('order_discount')->title('Order Discount')->addClass('text-end')->searchable(false),
            Column::make('grand_total')->title('Grand Total')->addClass('text-end')->searchable(false),
            Column::make('status')->title('Status')->orderable(false)->searchable(false),
            Column::computed('action')
                ->exportable(false)->printable(false)
                ->width(90)->addClass('text-center no-modal'),
        ];
    }

    protected function filename(): string
    {
        return 'Purchases_' . date('YmdHis');
    }
}
