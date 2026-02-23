<?php

namespace App\Http\Controllers\Reports;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Exports\ProductSalesExport;
use Exception;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;
class ReportController extends Controller
{
    public function productSales(Request $request)
    {
        // gather filter inputs
        $product = $request->query('product');     // text
        $branch = $request->query('branch');      // id
        $category = $request->query('category');    // id
        $customer = $request->query('customer');    // id
        $warehouse = $request->query('warehouse');   // id
        $from = $request->query('from');
        $to = $request->query('to');

        // load filter option lists for selects (adjust table names if different)
        $branches = DB::table('branches')->select('id', 'name')->orderBy('name')->get();
        $categories = DB::table('categories')->select('id', 'name')->orderBy('name')->get();
        $customers = DB::table('customers')->select('id', 'name')->orderBy('name')->get();
        $warehouses = DB::table('warehouses')->select('id', 'name')->orderBy('name')->get();

        $sql = <<<SQL
            SELECT
            c.name AS category_name,
            w.name AS warehouse_name,
            p.id AS product_id,
            p.name AS product_name,
            p.code AS product_code, 
            si.qty AS sale_qty,
            si.unit_price AS unit_price,
            u_sale.name AS sale_unit_name,
            p.unit_id AS base_unit_id,
            u_base.name AS base_unit_name,

            CASE
                WHEN uc.operator = '*' THEN si.qty * uc.numerator
                WHEN uc.operator = '/' THEN si.qty / NULLIF(uc.numerator,0)
                ELSE si.qty
            END AS converted_qty_to_base,

            ( CASE
                WHEN uc.operator = '*' THEN si.qty * uc.numerator
                WHEN uc.operator = '/' THEN si.qty / NULLIF(uc.numerator,0)
                ELSE si.qty
                END
            ) * p.cost AS total_cost,

            (si.qty * si.unit_price) AS total_price,

            ( (si.qty * si.unit_price) -
                ( CASE
                    WHEN uc.operator = '*' THEN si.qty * uc.numerator
                    WHEN uc.operator = '/' THEN si.qty / NULLIF(uc.numerator,0)
                    ELSE si.qty
                END
                ) * p.cost
            ) AS gross_profit,

            s.biller_id, s.customer_id, s.warehouse_id
            FROM sale_items si
            JOIN products p ON p.id = si.product_id
            LEFT JOIN units u_sale ON u_sale.id = si.unit_id
            LEFT JOIN units u_base ON u_base.id = p.unit_id
            LEFT JOIN unit_converts uc
                ON uc.unit_from_id = si.unit_id
                AND uc.unit_to_id   = p.unit_id
                AND uc.is_active = 1
            LEFT JOIN categories c ON c.id = p.category_id
            LEFT JOIN sales s ON s.id = si.sale_id
            LEFT JOIN warehouses w ON w.id = s.warehouse_id 
            WHERE 1=1
            SQL;

        $bindings = [];

        // apply filters (append SQL and bindings)
        if (!empty($product)) {
            // search product by name or code
            $sql .= "\n AND (p.name LIKE ? OR p.code LIKE ?)";
            $bindings[] = "%{$product}%";
            $bindings[] = "%{$product}%";
        }
        if (!empty($branch)) {
            $sql .= "\n AND s.branch_id = ?";
            $bindings[] = $branch;
        }
        if (!empty($category)) {
            $sql .= "\n AND p.category_id = ?";
            $bindings[] = $category;
        }
        if (!empty($customer)) {
            $sql .= "\n AND s.customer_id = ?";
            $bindings[] = $customer;
        }
        if (!empty($warehouse)) {
            // <-- filter on s.warehouse_id (sales table), not si.warehouse_id
            $sql .= "\n AND s.warehouse_id = ?";
            $bindings[] = $warehouse;
        }
        if (!empty($from)) {
            $sql .= "\n AND s.created_at >= ?";
            $bindings[] = $from . ' 00:00:00';
        }
        if (!empty($to)) {
            $sql .= "\n AND s.created_at <= ?";
            $bindings[] = $to . ' 23:59:59';
        }

        // ordering
        $sql .= "\n ORDER BY c.name, p.name";

        // fetch rows
        $rows = DB::select($sql, $bindings);

        // compute grand totals server-side (same as before)
        $totals = [
            'total_qty' => 0,
            'total_cost' => 0.0,
            'total_price' => 0.0,
            'total_profit' => 0.0,
        ];

        foreach ($rows as $r) {
            $totals['total_qty'] += (float) $r->sale_qty;
            $totals['total_cost'] += (float) $r->total_cost;
            $totals['total_price'] += (float) $r->total_price;
            $totals['total_profit'] += (float) $r->gross_profit;
        }

        // return view with data + lists for selects
        return view('reports.product_sales_report', [
            'rows' => $rows,
            'totals' => $totals,
            'from' => $from,
            'to' => $to,
            'product' => $product,
            'branch' => $branch,
            'category' => $category,
            'customer' => $customer,
            'warehouse' => $warehouse,
            'branches' => $branches,
            'categories' => $categories,
            'customers' => $customers,
            'warehouses' => $warehouses,
        ]);
    }


    public function exportProductSales(Request $request)
    {
        $filters = $request->only(['product', 'branch', 'category', 'customer', 'warehouse', 'from', 'to']);

        try {
            $filename = 'product_sales_' . now()->format('Ymd_His') . '.xlsx';
            return Excel::download(new ProductSalesExport($filters), $filename);
        } catch (Exception $e) {
            // Log and redirect back with friendly message
            Log::error('Product export failed: ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
            return back()->with('error', 'Export failed: ' . $e->getMessage());
        }
    }

}
