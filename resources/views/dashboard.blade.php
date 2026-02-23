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

        /* Gradient Header Section */
        .welcome-banner {
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            border-radius: 20px;
            padding: 30px;
            color: white;
            margin-bottom: 35px;
            box-shadow: 0 10px 30px rgba(67, 97, 238, 0.3);
        }

        /* Premium KPI Cards */
        .kpi-card {
            border: none;
            border-radius: 16px;
            background: var(--glass-white);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
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

        /* Chart Containers */
        .chart-box {
            background: #ffffff;
            border-radius: 20px;
            padding: 25px;
            border: none;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
            height: 100%;
        }

        .chart-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }

        .chart-header h4 {
            font-size: 1.1rem;
            font-weight: 700;
            color: var(--dark-text);
            margin: 0;
        }

        /* Decorative Badge */
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
        <div class="welcome-banner d-flex justify-content-between align-items-center">
            <div>
                <h1 class="h3 fw-bold mb-1">Business Overview</h1>
                <p class="mb-0 opacity-75">Analytics for the current fiscal period</p>
            </div>
        </div>

        <div class="row g-4 mb-5">
            <div class="col-xl-3 col-md-6">
                <div class="card kpi-card p-4">
                    <div class="kpi-icon-circle bg-primary text-white">
                        <i class="fas fa-chart-line fa-lg"></i>
                    </div>
                    <span class="text-muted small fw-bold text-uppercase">Total Revenue</span>
                    <h2 class="fw-bold mt-1">$24,580</h2>
                    <span class="text-success small"><i class="fas fa-arrow-up me-1"></i>12% vs last week</span>
                </div>
            </div>
            <div class="col-xl-3 col-md-6">
                <div class="card kpi-card p-4">
                    <div class="kpi-icon-circle bg-success text-white">
                        <i class="fas fa-box-open fa-lg"></i>
                    </div>
                    <span class="text-muted small fw-bold text-uppercase">Stock Level</span>
                    <h2 class="fw-bold mt-1">847</h2>
                    <div class="progress mt-2" style="height: 6px;">
                        <div class="progress-bar bg-success" style="width: 75%"></div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6">
                <div class="card kpi-card p-4">
                    <div class="kpi-icon-circle bg-info text-white">
                        <i class="fas fa-shopping-cart fa-lg"></i>
                    </div>
                    <span class="text-muted small fw-bold text-uppercase">Orders Today</span>
                    <h2 class="fw-bold mt-1">156</h2>
                    <span class="text-muted small">Target: 200</span>
                </div>
            </div>
            <div class="col-xl-3 col-md-6">
                <div class="card kpi-card p-4">
                    <div class="kpi-icon-circle bg-danger text-white">
                        <i class="fas fa-exclamation-circle fa-lg"></i>
                    </div>
                    <span class="text-muted small fw-bold text-uppercase">Low Stock Alert</span>
                    <h2 class="fw-bold mt-1 text-danger">23</h2>
                    <span class="status-badge">Action Required</span>
                </div>
            </div>
        </div>

        <div class="row g-4">
            <div class="col-lg-8">
                <div class="chart-box">
                    <div class="chart-header">
                        <h4><i class="fas fa-wave-square me-2 text-primary"></i>Performance Timeline</h4>
                        <div class="dropdown">
                            <button class="btn btn-outline-secondary btn-sm dropdown-toggle rounded-pill" type="button" data-bs-toggle="dropdown">Last 7 Days</button>
                        </div>
                    </div>
                    <div id="daily-trends-chart" style="height: 400px;"></div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="chart-box">
                    <div class="chart-header">
                        <h4><i class="fas fa-pie-chart me-2 text-primary"></i>Revenue Mix</h4>
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

                // Data from Laravel
                const timelineLabels = @json($timelineLabels);
                const timelineData   = @json($timelineData);
                const revenueMix     = @json($revenueMix);

                /* ============================
                * Performance Timeline
                * ============================ */
                const timelineChart = echarts.init(
                    document.getElementById('daily-trends-chart')
                );

                timelineChart.setOption({
                    tooltip: { trigger: 'axis' },
                    grid: {
                        left: '3%',
                        right: '4%',
                        bottom: '3%',
                        containLabel: true
                    },
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
                        lineStyle: {
                            width: 4,
                            color: '#4361ee'
                        },
                        areaStyle: {
                            color: 'rgba(67,97,238,0.15)'
                        }
                    }]
                });

                /* ============================
                * Revenue Mix
                * ============================ */
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
                        itemStyle: {
                            borderRadius: 10,
                            borderColor: '#fff',
                            borderWidth: 2
                        },
                        label: {
                            formatter: '{b}\n{d}%'
                        },
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