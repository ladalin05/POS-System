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

class DashboardController extends Controller
{
    /**
     * Display the admin dashboard.
     */
    public function index()
    {
        $pageTitle = 'Dashboard';
        $timelineLabels = ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'];
        $timelineData   = [3200, 4100, 3800, 5200, 6100, 5800, 6980];

        // ===== Revenue Mix =====
        $revenueMix = [
            ['name' => 'Retail',    'value' => 14500],
            ['name' => 'Wholesale', 'value' => 8200],
            ['name' => 'Online',    'value' => 3200],
            ['name' => 'Other',     'value' => 1680],
        ];


        return view('dashboard', compact('pageTitle', 'timelineLabels', 'timelineData', 'revenueMix'));
    }
}
