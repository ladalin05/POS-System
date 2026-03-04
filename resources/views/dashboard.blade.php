<x-app-layout>
    <style>
        :root {
            --primary: #4361ee;
            --secondary: #3f37c9;
            --success: #4cc9f0;
            --warning: #f72585;
            --info: #4895ef;
            --dark-text: #2b2d42;
            --light-bg: #f8f9fc;
            --glass-white: rgba(255, 255, 255, 0.9);
        }

        body {
            background-color: var(--light-bg);
            color: var(--dark-text);
            font-family: 'Inter', sans-serif;
        }

        .dashboard-container {
            padding: 30px;
        }

        .welcome-banner {
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            border-radius: 20px;
            padding: 30px;
            color: white;
            margin-bottom: 35px;
            box-shadow: 0 10px 30px rgba(67, 97, 238, 0.3);
        }

        .kpi-card {
            border: none;
            border-radius: 16px;
            min-height: 224px;
            background: var(--glass-white);
            transition: all 0.3s ease;
            overflow: hidden;
            border: 1px solid rgba(255, 255, 255, 0.3);
        }

        .kpi-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1);
        }

        .kpi-icon-circle {
            width: 55px;
            height: 55px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 15px;
        }

        .chart-box {
            background: #ffffff;
            border-radius: 20px;
            padding: 25px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
            height: 100%;
        }

        .chart-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }

        .status-badge {
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 600;
            background: #eef2ff;
            color: var(--primary);
        }
    </style>

    <div class="dashboard-container">

        {{-- HEADER --}}
        <div class="welcome-banner d-flex justify-content-between align-items-center">
            <div>
                <h1 class="h3 fw-bold mb-1">Business Overview</h1>
                <p class="mb-0 opacity-75">
                    Analytics for {{ now()->format('F Y') }}
                </p>
            </div>
        </div>

        {{-- KPI SECTION --}}
        <div class="row g-4 mb-5">

            {{-- TOTAL REVENUE --}}
            <div class="col-xl-3 col-md-6">
                <div class="card kpi-card p-4">
                    <div class="kpi-icon-circle bg-primary text-white">
                        <i class="fas fa-chart-line fa-lg"></i>
                    </div>
                    <span class="text-muted small fw-bold text-uppercase">Total Revenue</span>
                    <h2 class="fw-bold mt-1">
                        ${{ number_format($totalRevenue, 2) }}
                    </h2>
                    <span class="{{ $percentage >= 0 ? 'text-success' : 'text-danger' }} small"><i class="fas fa-{{ $percentage >= 0 ? 'arrow-up' : 'arrow-down' }} me-1"></i>{{ number_format($percentage, 0) }}% vs last week</span>
                </div>
            </div>

            {{-- STOCK LEVEL --}}
            <div class="col-xl-3 col-md-6">
                <div class="card kpi-card p-4">
                    <div class="kpi-icon-circle bg-success text-white">
                        <i class="fas fa-box-open fa-lg"></i>
                    </div>
                    <span class="text-muted small fw-bold text-uppercase">Stock Level</span>
                    <h2 class="fw-bold mt-1">
                        {{ number_format($stockLevel) }}
                    </h2>

                    @php
                        $maxStock = 1000; // adjust if needed
                        $percent = min(100, ($stockLevel / $maxStock) * 100);
                    @endphp

                    <div class="progress mt-2" style="height: 6px;">
                        <div class="progress-bar bg-success" style="width: {{ $percent }}%"></div>
                    </div>
                </div>
            </div>

            {{-- ORDERS TODAY --}}
            <div class="col-xl-3 col-md-6">
                <div class="card kpi-card p-4">
                    <div class="kpi-icon-circle bg-info text-white">
                        <i class="fas fa-shopping-cart fa-lg"></i>
                    </div>
                    <span class="text-muted small fw-bold text-uppercase">Orders Today</span>
                    <h2 class="fw-bold mt-1">
                        {{ $ordersToday }}
                    </h2>
                    <span class="text-muted small">
                        {{ now()->format('d M Y') }}
                    </span>
                </div>
            </div>

            {{-- LOW STOCK --}}
            <div class="col-xl-3 col-md-6">
                <div class="card kpi-card p-4">
                    <div class="kpi-icon-circle bg-danger text-white">
                        <i class="fas fa-exclamation-circle fa-lg"></i>
                    </div>
                    <span class="text-muted small fw-bold text-uppercase">Low Stock Alert</span>
                    <h2 class="fw-bold mt-1 text-danger">
                        {{ $lowStock }}
                    </h2>

                    @if($lowStock > 0)
                        <span class="status-badge">Action Required</span>
                    @else
                        <span class="text-success small">All Good</span>
                    @endif
                </div>
            </div>

        </div>

        {{-- CHART SECTION --}}
        <div class="row g-4">

            {{-- PERFORMANCE TIMELINE --}}
            <div class="col-lg-8">
                <div class="chart-box">
                    <div class="chart-header">
                        <h4>
                            <i class="fas fa-wave-square me-2 text-primary"></i>
                            Performance Timeline
                        </h4>
                        <span class="text-muted small">
                            Last 7 Days
                        </span>
                    </div>
                    <div id="daily-trends-chart" style="height: 400px;"></div>
                </div>
            </div>

            {{-- REVENUE MIX --}}
            <div class="col-lg-4">
                <div class="chart-box">
                    <div class="chart-header">
                        <h4>
                            <i class="fas fa-pie-chart me-2 text-primary"></i>
                            Revenue Mix
                        </h4>
                    </div>
                    <div id="sales-category-chart" style="height: 400px;"></div>
                </div>
            </div>

        </div>
    </div>

    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/echarts@5.4.3/dist/echarts.min.js"></script>

        <script>
            document.addEventListener('DOMContentLoaded', function () {

                const timelineLabels = @json($timelineLabels);
                const timelineData   = @json($timelineData);
                const revenueMix     = @json($revenueMix);

                /* Timeline Chart */
                const timelineChart = echarts.init(
                    document.getElementById('daily-trends-chart')
                );

                timelineChart.setOption({
                    tooltip: { trigger: 'axis' },
                    xAxis: {
                        type: 'category',
                        data: timelineLabels
                    },
                    yAxis: {
                        type: 'value'
                    },
                    series: [{
                        name: 'Revenue ($)',
                        type: 'line',
                        smooth: true,
                        data: timelineData,
                        lineStyle: { width: 4, color: '#4361ee' },
                        areaStyle: { color: 'rgba(67,97,238,0.15)' }
                    }]
                });

                /* Revenue Mix */
                const revenueChart = echarts.init(
                    document.getElementById('sales-category-chart')
                );

                revenueChart.setOption({
                    tooltip: {
                        trigger: 'item',
                        formatter: '{b}: ${c} ({d}%)'
                    },
                    legend: {
                        bottom: '0%',
                        left: 'center'
                    },
                    series: [{
                        type: 'pie',
                        radius: ['40%', '70%'],
                        data: revenueMix
                    }]
                });

                window.addEventListener('resize', function () {
                    timelineChart.resize();
                    revenueChart.resize();
                });

            });
        </script>
    @endpush
</x-app-layout>