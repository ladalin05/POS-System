<?php

namespace App\DataTables\Sales;

use App\Models\Adjustment\StockMove;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Services\DataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\SoftDeletingScope;
class StockCountDataTable extends DataTable
{


    /**
     * Compute human-friendly qty breakdown like "2 case, 4 can"
     * @param int $productId
     * @param int|float $qty_in_base  // stock_qty from your SQL (in product base unit)
     * @param int $baseUnitId         // p.unit_id
     * @return string
     */
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))

            ->addColumn('product_code', fn($r) => $r->product->code ?? '-')
            ->addColumn('product_name_with_unit', function ($r) {

                $name = $r->product->name ?? '-';
                $unit = $r->unit_name ?? '';

                // Always show name + unit
                return $unit ? ($name . ' (' . $unit . ')') : $name;
            })

            // new unit_convert column (shows same cu.name value)
            ->addColumn('base_unit_name', fn($r) => $r->base_unit_name ?? '-')

            ->addColumn('warehouse', fn($r) => $r->warehouse_name ?? '-')

            ->editColumn('stock_qty', fn($r) => number_format((float) $r->stock_qty, 2))

            ->setRowId('product_id')
            ->addColumn('qty_unit', function ($row) {
                // $row must have product_id, stock_qty, base_unit_id from your select
                return $this->computeQtyBreakdown($row->product_id, $row->stock_qty, $row->base_unit_id);
            });


    }

    protected function computeQtyBreakdown(int $productId, $qty_in_base, int $baseUnitId): string
    {
        // ensure numeric
        $qty = (float) $qty_in_base;
        if ($qty <= 0)
            return '0';

        // 1) Start with base unit
        $units = [];
        $baseUnit = DB::table('units')->where('id', $baseUnitId)->first();
        if ($baseUnit) {
            $units[$baseUnit->id] = (object) [
                'id' => $baseUnit->id,
                'name' => $baseUnit->name,
                'factor' => 1.0
            ];
        }

        // 2) Get conversions that are directly to/from the base unit
        $rows = DB::table('unit_converts')
            ->where('unit_to_id', $baseUnitId)
            ->orWhere('unit_from_id', $baseUnitId)
            ->get();

        foreach ($rows as $r) {
            // case A: a row unit_from -> unit_to(base)
            if ($r->unit_to_id == $baseUnitId) {
                $factor = null;
                if ($r->operator === '*') {
                    $factor = (float) $r->numerator;          // 1 unit_from = numerator base units
                } elseif ($r->operator === '/') {
                    $factor = 1.0 / (float) $r->numerator;    // 1 unit_from = 1/numerator base units
                } else {
                    $factor = (float) $r->numerator;
                }
                if ($factor > 0) {
                    $u = DB::table('units')->where('id', $r->unit_from_id)->first();
                    if ($u)
                        $units[$u->id] = (object) ['id' => $u->id, 'name' => $u->name, 'factor' => $factor];
                }
            }

            // case B: a row base -> other (we need reciprocal)
            if ($r->unit_from_id == $baseUnitId) {
                $factor = null;
                if ($r->operator === '*') {
                    // 1 base = numerator other -> so 1 other = 1/numerator base
                    $factor = 1.0 / (float) $r->numerator;
                } elseif ($r->operator === '/') {
                    // 1 base = (1/numerator) other => 1 other = numerator base
                    $factor = (float) $r->numerator;
                } else {
                    $factor = 1.0 / (float) $r->numerator;
                }
                if ($factor > 0) {
                    $u = DB::table('units')->where('id', $r->unit_to_id)->first();
                    if ($u)
                        $units[$u->id] = (object) ['id' => $u->id, 'name' => $u->name, 'factor' => $factor];
                }
            }
        }

      
        usort($units, function ($a, $b) {
            return $b->factor <=> $a->factor;
        });

    
        $parts = [];
        foreach ($units as $unit) {
         
            if (!isset($unit->factor) || $unit->factor <= 0)
                continue;

            // choose only units that can fit into current qty
            if ($unit->factor <= $qty) {
                $count = floor($qty / $unit->factor);
                if ($count > 0) {
                    $parts[] = $count . ' ' . $unit->name;
                    $qty -= $count * $unit->factor;
                    // small floating rounding guard
                    $qty = round($qty, 8);
                }
            }
        }

        // if nothing matched (qty smaller than smallest factor), show qty with base unit name
        if (empty($parts)) {
            $baseName = $baseUnit->name ?? 'unit';
            // show qty as integer if whole, otherwise show decimal trimmed
            $qtyLabel = (floor($qty) == $qty) ? (int) $qty : rtrim(rtrim((string) $qty, '0'), '.');
            return $qtyLabel . ' ' . $baseName;
        }

        return implode(', ', $parts);
    }
    public function query(StockMove $model): QueryBuilder
    {
        return $model->newQuery()
            ->withoutGlobalScope(SoftDeletingScope::class)
            ->from('stockmoves as sm')
            ->selectRaw("
            p.id AS product_id,
            p.code,
            p.name,
            p.unit_id AS base_unit_id,
            tu.name AS base_unit_name,
            w.name AS warehouse_name,

            SUM(
                CASE
                    WHEN LOWER(sm.transaction) = 'sale' THEN -ABS(sm.unit_quantity)
                    WHEN LOWER(sm.transaction) = 'purchase' THEN ABS(sm.unit_quantity)
                    ELSE sm.unit_quantity
                END
            ) AS stock_qty

             
        ")

            ->join('products as p', 'p.id', '=', 'sm.product_id')
            ->leftJoin('units as tu', 'tu.id', '=', 'p.unit_id')

            ->leftJoin('warehouses as w', 'w.id', '=', 'sm.warehouse_id')   

            ->leftJoin(DB::raw("(
            SELECT unit_from_id, unit_to_id,
                CASE WHEN operator = '*' THEN CAST(numerator AS DECIMAL(20,8))
                     WHEN operator = '/' THEN 1 / CAST(numerator AS DECIMAL(20,8))
                     ELSE CAST(numerator AS DECIMAL(20,8))
                END AS factor
            FROM unit_converts

            UNION ALL

            SELECT unit_to_id AS unit_from_id, unit_from_id AS unit_to_id,
                CASE WHEN operator = '*' THEN 1 / CAST(numerator AS DECIMAL(20,8))
                     WHEN operator = '/' THEN CAST(numerator AS DECIMAL(20,8))
                     ELSE 1 / CAST(numerator AS DECIMAL(20,8))
                END AS factor
            FROM unit_converts
        ) conv"), function ($join) {
                $join->on('conv.unit_from_id', '=', 'sm.unit_id')
                    ->on('conv.unit_to_id', '=', 'p.unit_id');
            })

            ->groupBy(
                'p.id',
                'p.code',
                'p.name',
                'p.unit_id',
                'tu.name',
                'w.name'     // ✅ GROUP BY warehouse
            )
            ->orderBy('p.code');
    }





    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('stock_count-table')
            ->columns($this->getColumns())
            ->minifiedAjax()
            ->orderBy(1, 'desc')
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
            Column::make('product_code')->title('Product Code')->orderable(false),
            Column::make('product_name_with_unit')->title('Product Name (Unit)')->orderable(false),
            Column::make('warehouse')->title('Warehouse')->orderable(false),
            Column::make('base_unit_name')->title('Unit')->orderable(false), // NEW COLUMN
            Column::make('qty_unit')->title('Qty Unit')->orderable(false),
            Column::make('stock_qty')->title('Stock On Hand')->addClass('text-end'),


        ];
    }

    protected function filename(): string
    {
        return 'StockCount_' . date('YmdHis');
    }
}
