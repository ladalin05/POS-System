<?php

namespace App\Http\Controllers;

use App\Models\UserManagement\User;
use App\Models\Type;
use App\Models\Property;
use App\Models\Reports;
use App\Models\Admin\Transactions;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Artisan;
use App\Http\Controllers\Controller;
use App\Models\Product\Product;
use App\Models\Sales\SaleItems;
use App\Models\Sales\Sales;
use App\Models\Stocks\ProductStock;
use Illuminate\Support\Carbon;

class DashboardController extends Controller
{   
    public function index()
    {
        // ✅ Total Revenue (Completed Sales Only)
        $totalRevenue = Sales::where('status', 'completed')
                        ->sum('grand_total');

        // ✅ Orders Today
        $ordersToday = Sales::whereDate('date', Carbon::today())
                    ->where('status', 'completed')
                    ->count();

        // ✅ Total Stock Level (Track Quantity Only)
        $stockLevel = ProductStock::sum('stock');

        // ✅ Low Stock Alert
        $lowStock = ProductStock::whereColumn('stock', '<=', 'alert_quantity')->count();


        /* =====================================================
         * PERFORMANCE TIMELINE (Last 7 Days)
         * ===================================================== */

        $timelineLabels = [];
        $timelineData   = [];

        for ($i = 6; $i >= 0; $i--) {

            $date = Carbon::today()->subDays($i);

            $timelineLabels[] = $date->format('d M');

            $dailyRevenue = Sales::whereDate('date', $date)
                ->where('status', 'completed')
                ->sum('grand_total');

            $timelineData[] = (float) $dailyRevenue;
        }


        /* =====================================================
         * REVENUE MIX (Top 5 Products)
         * ===================================================== */

        $revenueMix = SaleItems::join('sales', 'sale_items.sale_id', '=', 'sales.id')
            ->join('products', 'sale_items.product_id', '=', 'products.id')
            ->where('sales.status', 'completed')
            ->select(
                'products.name',
                DB::raw('SUM(sale_items.subtotal) as total')
            )
            ->groupBy('products.name')
            ->orderByDesc('total')
            ->limit(5)
            ->get()
            ->map(function ($item) {
                return [
                    'value' => (float) $item->total,
                    'name'  => $item->name
                ];
            });

        $thisWeek = Sales::where('status', 'completed')
                        ->where('date', '>=', now()->startOfWeek())
                        ->where('date', '<=', now()->endOfWeek())
                        ->sum('grand_total');

        $lastWeek = Sales::where('status', 'completed')
                        ->where('date', '>=', now()->subWeek()->startOfWeek())
                        ->where('date', '<=', now()->subWeek()->endOfWeek())
                        ->sum('grand_total');

        $percentage = $lastWeek > 0 ? (($thisWeek - $lastWeek) / $lastWeek) * 100 : 0;

        return view('dashboard', compact(
            'totalRevenue',
            'ordersToday',
            'stockLevel',
            'lowStock',
            'timelineLabels',
            'timelineData',
            'revenueMix',
            'percentage'
        ));
    }
}
