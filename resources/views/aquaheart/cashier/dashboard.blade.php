@extends('layouts.aquaheart')

@section('title', 'Cashier Dashboard')
@section('page_title', 'AquaHeart POS')
@section('page_subtitle', 'Seamless transaction management & business insights.')

@section('page_actions')
<div class="header-actions">
    <a href="{{ route('aquaheart.refills.create') }}" class="btn-action-premium">
        <div class="btn-icon-wrapper"><i data-lucide="plus"></i></div>
        <span>New Transaction</span>
    </a>
    <a href="{{ route('aquaheart.customers.index') }}" class="btn-action-outline">
        <i data-lucide="users" size="18"></i>
        Find Customer
    </a>
</div>
@endsection

@section('content')
@php
    $activeCustomers = \App\Models\Customer::count();
@endphp

<div class="stats-container">
    <div class="premium-stat-card aqua">
        <div class="stat-inner">
            <div class="stat-info">
                <span class="stat-tag">Today</span>
                <h3 class="stat-value">{{ number_format($todayTransactions) }}</h3>
                <p class="stat-desc">Shift Transactions</p>
            </div>
            <div class="stat-visual">
                <div class="stat-icon-blob"><i data-lucide="shopping-bag"></i></div>
            </div>
        </div>
        <div class="stat-progress">
            <div class="progress-track"><div class="progress-fill" style="width: 65%"></div></div>
        </div>
    </div>

    <div class="premium-stat-card emerald">
        <div class="stat-inner">
            <div class="stat-info">
                <span class="stat-tag">Revenue</span>
                <h3 class="stat-value">₱{{ number_format($todayRevenue, 2) }}</h3>
                <p class="stat-desc">Daily Collections</p>
            </div>
            <div class="stat-visual">
                <div class="stat-icon-blob"><i data-lucide="banknote"></i></div>
            </div>
        </div>
        <div class="stat-progress">
            <div class="progress-track"><div class="progress-fill" style="width: 80%"></div></div>
        </div>
    </div>

    <div class="premium-stat-card indigo">
        <div class="stat-inner">
            <div class="stat-info">
                <span class="stat-tag">Overall</span>
                <h3 class="stat-value">₱{{ number_format($totalRevenue, 2) }}</h3>
                <p class="stat-desc">Career Sales</p>
            </div>
            <div class="stat-visual">
                <div class="stat-icon-blob"><i data-lucide="award"></i></div>
            </div>
        </div>
        <div class="stat-progress">
            <div class="progress-track"><div class="progress-fill" style="width: 45%"></div></div>
        </div>
    </div>
</div>

<div class="dashboard-layout-grid">
    <div class="dashboard-left-col">
        <div class="glass-card transactions-card">
            <div class="card-top-flex">
                <div class="card-heading">
                    <h3>Recent Stream</h3>
                    <p>Live activity of your processed sales</p>
                </div>
                <a href="{{ route('aquaheart.refills.index') }}" class="view-all-link">History <i data-lucide="chevron-right"></i></a>
            </div>
            
            <div class="transaction-stream">
                @forelse($recentTransactions as $refill)
                    <div class="stream-item" onclick="window.location='{{ route('aquaheart.refills.show', $refill) }}'">
                        <div class="stream-icon-box">
                            <i data-lucide="arrow-up-right"></i>
                        </div>
                        <div class="stream-content">
                            <div class="stream-main-info">
                                <span class="stream-title">{{ $refill->customer->name ?? 'Walk-in Customer' }}</span>
                                <span class="stream-amount">₱{{ number_format(($refill->quantity ?? 0) * ($refill->unit_price ?? 0), 2) }}</span>
                            </div>
                            <div class="stream-sub-info">
                                <span class="stream-meta"><i data-lucide="hash"></i> {{ $refill->receipt_number }}</span>
                                <span class="stream-meta"><i data-lucide="package"></i> {{ $refill->product->name ?? 'Refill' }}</span>
                                <span class="stream-time">{{ $refill->created_at->diffForHumans() }}</span>
                            </div>
                        </div>
                        <div class="stream-status">
                            @php $status = $refill->payment_status ?? 'paid'; @endphp
                            <span class="badge-premium {{ $status }}">{{ ucfirst($status) }}</span>
                        </div>
                    </div>
                @empty
                    <div class="empty-state-simple">
                        <i data-lucide="inbox"></i>
                        <p>No transactions yet today</p>
                    </div>
                @endforelse
            </div>
        </div>

        <div class="charts-row">
            <div class="glass-card chart-item">
                <div class="card-heading">
                    <h3>Performance Dynamics</h3>
                    <p>30-day revenue trend</p>
                </div>
                <div class="chart-wrapper">
                    <canvas id="salesChart"></canvas>
                </div>
            </div>
            <div class="glass-card chart-item">
                <div class="card-heading">
                    <h3>Volume Analysis</h3>
                    <p>Monthly sales breakdown</p>
                </div>
                <div class="chart-wrapper">
                    <canvas id="monthlySalesChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    <aside class="dashboard-right-col">
        <div class="glass-card command-panel">
            <div class="card-heading">
                <h3>Command Center</h3>
                <p>One-tap POS actions</p>
            </div>
            <div class="command-grid">
                <a href="{{ route('aquaheart.refills.create') }}" class="command-btn">
                    <div class="cmd-icon aqua"><i data-lucide="plus-square"></i></div>
                    <span>New Sale</span>
                </a>
                <a href="{{ route('aquaheart.customers.index') }}" class="command-btn">
                    <div class="cmd-icon emerald"><i data-lucide="user-plus"></i></div>
                    <span>Customers</span>
                </a>
                <a href="{{ route('aquaheart.refills.index') }}" class="command-btn">
                    <div class="cmd-icon indigo"><i data-lucide="file-text"></i></div>
                    <span>Reports</span>
                </a>
                <a href="{{ route('aquaheart.support') }}" class="command-btn">
                    <div class="cmd-icon amber"><i data-lucide="life-buoy"></i></div>
                    <span>Support</span>
                </a>
            </div>
        </div>

        <div class="glass-card inventory-pulse">
            <div class="card-heading">
                <h3>Inventory Pulse</h3>
                <p>Critical stock monitoring</p>
            </div>
            <div class="pulse-list">
                @foreach(\App\Models\Product::where('is_active', true)->orderBy('stock_quantity')->take(5)->get() as $product)
                <div class="pulse-item">
                    <div class="pulse-info">
                        <span class="pulse-name">{{ $product->name }}</span>
                        <span class="pulse-stock {{ $product->stock_quantity <= $product->reorder_level ? 'low' : '' }}">
                            {{ $product->stock_quantity }} units left
                        </span>
                    </div>
                    <div class="pulse-progress-container">
                        @php $perc = min(100, ($product->stock_quantity / ($product->reorder_level * 3 ?: 100)) * 100); @endphp
                        <div class="pulse-progress-bar {{ $product->stock_quantity <= $product->reorder_level ? 'critical' : '' }}" style="width: {{ $perc }}%"></div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>

        <div class="upgrade-card-simple">
            <div class="upgrade-content">
                <h4>System Update</h4>
                <p>New version v2.4 is live with improved analytics.</p>
            </div>
            <i data-lucide="sparkles"></i>
        </div>
    </aside>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
    Chart.defaults.font.family = "'Plus Jakarta Sans', sans-serif";
    Chart.defaults.color = "#94a3b8";

    // Daily Sales Chart
    const dailySalesCtx = document.getElementById('salesChart');
    if (dailySalesCtx) {
        new Chart(dailySalesCtx, {
            type: 'line',
            data: {
                labels: @json($dailySalesLabels),
                datasets: [{
                    label: 'Revenue',
                    data: @json($dailySalesData),
                    borderColor: '#3b82f6',
                    backgroundColor: (context) => {
                        const chart = context.chart;
                        const {ctx, chartArea} = chart;
                        if (!chartArea) return null;
                        const gradient = ctx.createLinearGradient(0, chartArea.bottom, 0, chartArea.top);
                        gradient.addColorStop(0, 'rgba(59, 130, 246, 0)');
                        gradient.addColorStop(1, 'rgba(59, 130, 246, 0.1)');
                        return gradient;
                    },
                    borderWidth: 3,
                    fill: true,
                    tension: 0.4,
                    pointRadius: 0,
                    pointHoverRadius: 6,
                    pointHoverBackgroundColor: '#3b82f6',
                    pointHoverBorderColor: '#fff',
                    pointHoverBorderWidth: 3,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false }, tooltip: { mode: 'index', intersect: false } },
                scales: {
                    y: { beginAtZero: true, grid: { color: 'rgba(226, 232, 240, 0.5)', drawBorder: false }, ticks: { callback: v => '₱' + v.toLocaleString() } },
                    x: { grid: { display: false } }
                }
            }
        });
    }

    // Monthly Sales Chart
    const monthlySalesCtx = document.getElementById('monthlySalesChart');
    if (monthlySalesCtx) {
        new Chart(monthlySalesCtx, {
            type: 'bar',
            data: {
                labels: @json($monthlySalesLabels),
                datasets: [{
                    label: 'Revenue',
                    data: @json($monthlySalesData),
                    backgroundColor: '#10b981',
                    borderRadius: 8,
                    maxBarThickness: 32,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                scales: {
                    y: { beginAtZero: true, grid: { color: 'rgba(226, 232, 240, 0.5)', drawBorder: false }, ticks: { callback: v => '₱' + v.toLocaleString() } },
                    x: { grid: { display: false } }
                }
            }
        });
    }
</script>
@endpush

@push('styles')
<style>
    /* Header Actions */
    .header-actions { display: flex; gap: 16px; align-items: center; }
    .btn-action-premium { background: var(--primary); color: white; text-decoration: none; padding: 6px 20px 6px 6px; border-radius: 100px; display: flex; align-items: center; gap: 12px; font-weight: 700; font-size: 0.9rem; transition: var(--transition); box-shadow: 0 10px 15px -3px rgba(15, 23, 42, 0.15); }
    .btn-action-premium:hover { transform: translateY(-2px); box-shadow: 0 20px 25px -5px rgba(15, 23, 42, 0.2); }
    .btn-icon-wrapper { width: 36px; height: 36px; border-radius: 50%; background: rgba(255,255,255,0.15); display: flex; align-items: center; justify-content: center; }
    .btn-action-outline { border: 1px solid var(--border); color: var(--text-main); text-decoration: none; padding: 10px 20px; border-radius: 100px; display: flex; align-items: center; gap: 10px; font-weight: 700; font-size: 0.9rem; transition: var(--transition); background: white; }
    .btn-action-outline:hover { background: var(--bg); border-color: var(--text-muted); }

    /* Stats Grid */
    .stats-container { display: grid; grid-template-columns: repeat(3, 1fr); gap: 24px; margin-bottom: 32px; }
    .premium-stat-card { background: white; border-radius: 24px; padding: 24px; position: relative; overflow: hidden; border: 1px solid var(--border); transition: var(--transition); }
    .premium-stat-card:hover { transform: translateY(-4px); box-shadow: 0 20px 25px -5px rgba(0,0,0,0.05); }
    .stat-inner { display: flex; justify-content: space-between; align-items: flex-start; position: relative; z-index: 2; }
    .stat-tag { font-size: 0.65rem; font-weight: 800; text-transform: uppercase; color: var(--text-muted); letter-spacing: 1px; }
    .stat-value { font-size: 1.75rem; font-weight: 800; color: var(--primary); margin: 4px 0; letter-spacing: -0.5px; }
    .stat-desc { font-size: 0.85rem; color: var(--text-muted); font-weight: 600; }
    .stat-icon-blob { width: 56px; height: 56px; border-radius: 18px; display: flex; align-items: center; justify-content: center; position: relative; }
    .stat-icon-blob i { width: 28px; height: 28px; stroke-width: 2.5; }
    
    .aqua .stat-icon-blob { background: #e0f2fe; color: #0ea5e9; }
    .emerald .stat-icon-blob { background: #dcfce7; color: #10b981; }
    .indigo .stat-icon-blob { background: #e0e7ff; color: #6366f1; }
    
    .stat-progress { margin-top: 20px; }
    .progress-track { height: 6px; background: #f1f5f9; border-radius: 10px; overflow: hidden; }
    .progress-fill { height: 100%; border-radius: 10px; }
    .aqua .progress-fill { background: #0ea5e9; }
    .emerald .progress-fill { background: #10b981; }
    .indigo .progress-fill { background: #6366f1; }

    /* Layout */
    .dashboard-layout-grid { display: grid; grid-template-columns: 1fr 340px; gap: 32px; }
    .dashboard-left-col { display: flex; flex-direction: column; gap: 32px; }
    .glass-card { background: white; border: 1px solid var(--border); border-radius: 24px; padding: 28px; }
    
    .card-top-flex { display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 24px; }
    .card-heading h3 { font-size: 1.15rem; font-weight: 800; color: var(--primary); letter-spacing: -0.3px; }
    .card-heading p { font-size: 0.85rem; color: var(--text-muted); font-weight: 500; }
    .view-all-link { color: var(--accent); text-decoration: none; font-size: 0.85rem; font-weight: 800; display: flex; align-items: center; gap: 4px; }

    /* Transaction Stream */
    .transaction-stream { display: flex; flex-direction: column; gap: 12px; }
    .stream-item { display: flex; align-items: center; gap: 16px; padding: 16px; border-radius: 18px; background: #f8fafc; cursor: pointer; transition: var(--transition); border: 1px solid transparent; }
    .stream-item:hover { background: white; border-color: var(--accent); transform: scale(1.01); box-shadow: 0 4px 12px rgba(59, 130, 246, 0.08); }
    .stream-icon-box { width: 44px; height: 44px; border-radius: 12px; background: white; display: flex; align-items: center; justify-content: center; color: var(--text-muted); border: 1px solid #e2e8f0; }
    .stream-content { flex: 1; }
    .stream-main-info { display: flex; justify-content: space-between; align-items: center; margin-bottom: 4px; }
    .stream-title { font-weight: 800; color: var(--primary); font-size: 0.95rem; }
    .stream-amount { font-weight: 900; color: var(--primary); font-size: 1rem; }
    .stream-sub-info { display: flex; align-items: center; gap: 16px; }
    .stream-meta { font-size: 0.75rem; color: var(--text-muted); display: flex; align-items: center; gap: 4px; font-weight: 600; }
    .stream-time { font-size: 0.75rem; color: var(--text-muted); margin-left: auto; font-weight: 600; }
    
    .badge-premium { padding: 4px 12px; border-radius: 100px; font-size: 0.7rem; font-weight: 800; text-transform: uppercase; letter-spacing: 0.5px; }
    .badge-premium.paid { background: #dcfce7; color: #166534; }
    .badge-premium.unpaid { background: #fee2e2; color: #991b1b; }
    .badge-premium.partial { background: #fef3c7; color: #92400e; }

    /* Charts */
    .charts-row { display: grid; grid-template-columns: 1fr 1fr; gap: 24px; }
    .chart-wrapper { height: 260px; margin-top: 20px; }

    /* Command Panel */
    .command-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 16px; margin-top: 20px; }
    .command-btn { text-decoration: none; display: flex; flex-direction: column; align-items: center; gap: 12px; padding: 20px; background: #f8fafc; border-radius: 20px; transition: var(--transition); border: 1px solid transparent; }
    .command-btn:hover { background: white; border-color: var(--accent); transform: translateY(-4px); box-shadow: 0 12px 20px -5px rgba(0,0,0,0.05); }
    .cmd-icon { width: 48px; height: 48px; border-radius: 14px; display: flex; align-items: center; justify-content: center; }
    .cmd-icon i { width: 24px; height: 24px; stroke-width: 2.5; }
    .cmd-icon.aqua { background: #e0f2fe; color: #0ea5e9; }
    .cmd-icon.emerald { background: #dcfce7; color: #10b981; }
    .cmd-icon.indigo { background: #e0e7ff; color: #6366f1; }
    .cmd-icon.amber { background: #fef3c7; color: #f59e0b; }
    .command-btn span { font-size: 0.8rem; font-weight: 800; color: var(--primary); }

    /* Inventory Pulse */
    .pulse-list { display: flex; flex-direction: column; gap: 20px; margin-top: 24px; }
    .pulse-item { display: flex; flex-direction: column; gap: 8px; }
    .pulse-info { display: flex; justify-content: space-between; align-items: flex-end; }
    .pulse-name { font-size: 0.85rem; font-weight: 700; color: var(--primary); }
    .pulse-stock { font-size: 0.75rem; font-weight: 800; color: var(--text-muted); }
    .pulse-stock.low { color: #ef4444; animation: blink 2s infinite; }
    @keyframes blink { 0% { opacity: 1; } 50% { opacity: 0.4; } 100% { opacity: 1; } }
    
    .pulse-progress-container { height: 8px; background: #f1f5f9; border-radius: 4px; overflow: hidden; }
    .pulse-progress-bar { height: 100%; background: var(--accent); border-radius: 4px; }
    .pulse-progress-bar.critical { background: #ef4444; }

    /* Upgrade Card */
    .upgrade-card-simple { margin-top: 32px; background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%); border-radius: 24px; padding: 24px; color: white; display: flex; align-items: center; gap: 16px; position: relative; overflow: hidden; }
    .upgrade-card-simple::after { content: ''; position: absolute; top: -50%; right: -20%; width: 120px; height: 120px; background: rgba(59, 130, 246, 0.2); filter: blur(40px); border-radius: 50%; }
    .upgrade-content h4 { font-size: 1rem; font-weight: 800; margin-bottom: 4px; }
    .upgrade-content p { font-size: 0.75rem; opacity: 0.7; font-weight: 600; line-height: 1.4; }
    .upgrade-card-simple i { color: #3b82f6; width: 32px; height: 32px; flex-shrink: 0; }

    @media (max-width: 1280px) {
        .dashboard-layout-grid { grid-template-columns: 1fr; }
        .dashboard-right-col { display: grid; grid-template-columns: 1fr 1fr; gap: 24px; }
        .upgrade-card-simple { grid-column: span 2; }
    }
    @media (max-width: 1024px) {
        .stats-container { grid-template-columns: 1fr 1fr; }
    }
    @media (max-width: 768px) {
        .stats-container { grid-template-columns: 1fr; }
        .dashboard-right-col { grid-template-columns: 1fr; }
        .charts-row { grid-template-columns: 1fr; }
    }
</style>
@endpush
@endsection
