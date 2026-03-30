@extends('layouts.aquaheart')

@section('title', 'Overview')
@section('page_title', 'Business Intelligence')
@section('page_subtitle', 'Real-time performance metrics, inventory status, and station overview.')

@push('scripts_header')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
@endpush

@section('page_actions')
<div style="display: flex; gap: 12px;">
    <a href="{{ route('aquaheart.refills.create') }}" class="btn-primary">
        <i data-lucide="plus" size="18"></i>
        New Transaction
    </a>
    <a href="{{ route('aquaheart.products.index') }}" class="btn-primary" style="background: white; color: var(--primary); border: 1px solid var(--border);">
        <i data-lucide="package-2" size="18"></i>
        Manage Products
    </a>
</div>
@endsection

@section('content')
@php
    $lowStockCount = \App\Models\Product::whereColumn('stock_quantity', '<=', 'reorder_level')->count();
    $recentProducts = \App\Models\Product::orderBy('stock_quantity')->take(6)->get();
@endphp

<div class="stats-row">
    <div class="card stat-card">
        <div class="stat-top">
            <span class="stat-label">Customers</span>
            <div class="stat-icon b-blue"><i data-lucide="users"></i></div>
        </div>
        <div class="stat-value">{{ \App\Models\Customer::count() }}</div>
        <div class="stat-footer">Registered households and accounts</div>
    </div>
    <div class="card stat-card">
        <div class="stat-top">
            <span class="stat-label">Transactions</span>
            <div class="stat-icon b-green"><i data-lucide="layers"></i></div>
        </div>
        <div class="stat-value">{{ \App\Models\Refill::count() }}</div>
        <div class="stat-footer">Logged refill and sales records</div>
    </div>
    <div class="card stat-card">
        <div class="stat-top">
            <span class="stat-label">Revenue</span>
            <div class="stat-icon b-orange"><i data-lucide="badge-dollar-sign"></i></div>
        </div>
        <div class="stat-value">PHP {{ number_format(\App\Models\Refill::sum('amount'), 2) }}</div>
        <div class="stat-footer">Gross sales recorded in the system</div>
    </div>
    <div class="card stat-card">
        <div class="stat-top">
            <span class="stat-label">Low Stock</span>
            <div class="stat-icon b-red"><i data-lucide="triangle-alert"></i></div>
        </div>
        <div class="stat-value">{{ $lowStockCount }}</div>
        <div class="stat-footer">Products at or below reorder level</div>
    </div>
</div>

<div class="performance-grid">
    <div class="card chart-card">
        <div class="card-header">
            <h3>Weekly Revenue Trend</h3>
            <p>Sales movement for the past 7 days</p>
        </div>
        <div class="chart-box">
            <canvas id="mainChart"></canvas>
        </div>
    </div>

    <div class="card logs-card">
        <div class="card-header">
            <h3>Recent Activity</h3>
            <p>Latest customer transactions</p>
        </div>
        <ul class="activity-list">
            @forelse(\App\Models\Refill::with('customer')->latest()->take(6)->get() as $refill)
                <li class="activity-item">
                    <div class="activity-avatar">{{ substr($refill->customer->name ?? '?', 0, 1) }}</div>
                    <div class="activity-data">
                        <span class="activity-name">{{ $refill->customer->name ?? 'Unknown Customer' }}</span>
                        <span class="activity-desc">{{ ucfirst(str_replace('_', ' ', $refill->service_type ?? 'walk_in')) }} on {{ $refill->refill_date->format('M d') }}</span>
                    </div>
                    <div class="activity-amt">PHP {{ number_format($refill->amount, 2) }}</div>
                </li>
            @empty
                <li class="empty-activity">No recent data available.</li>
            @endforelse
        </ul>
        <a href="{{ route('aquaheart.refills.index') }}" class="view-all">View all history -></a>
    </div>

    <div class="card logs-card">
        <div class="card-header">
            <h3>Inventory Snapshot</h3>
            <p>Products that need monitoring</p>
        </div>
        <ul class="activity-list">
            @forelse($recentProducts as $product)
                <li class="activity-item">
                    <div class="activity-avatar">{{ substr($product->name, 0, 1) }}</div>
                    <div class="activity-data">
                        <span class="activity-name">{{ $product->name }}</span>
                        <span class="activity-desc">Reorder level: {{ $product->reorder_level }}</span>
                    </div>
                    <div class="stock-pill {{ $product->stock_quantity <= $product->reorder_level ? 'low' : '' }}">
                        {{ $product->stock_quantity }}
                    </div>
                </li>
            @empty
                <li class="empty-activity">No products configured yet.</li>
            @endforelse
        </ul>
        <a href="{{ route('aquaheart.products.index') }}" class="view-all">Manage products -></a>
    </div>
</div>

@push('styles')
<style>
    .stats-row { display: grid; grid-template-columns: repeat(4, 1fr); gap: 24px; margin-bottom: 32px; }
    .stat-card { display: flex; flex-direction: column; gap: 12px; }
    .stat-top { display: flex; justify-content: space-between; align-items: center; }
    .stat-label { font-size: 0.75rem; font-weight: 700; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.5px; }
    .stat-icon { width: 36px; height: 36px; border-radius: 8px; display: flex; align-items: center; justify-content: center; }
    .stat-icon.b-blue { background: #eff6ff; color: #3b82f6; }
    .stat-icon.b-green { background: #f0fdf4; color: #22c55e; }
    .stat-icon.b-orange { background: #fff7ed; color: #f97316; }
    .stat-icon.b-red { background: #fef2f2; color: #ef4444; }
    .stat-value { font-size: 1.5rem; font-weight: 800; color: var(--primary); letter-spacing: -0.5px; }
    .stat-footer { font-size: 0.8rem; color: var(--text-muted); }

    .performance-grid { display: grid; grid-template-columns: 2fr 1fr 1fr; gap: 24px; }
    .card-header { margin-bottom: 24px; }
    .card-header h3 { font-size: 1.1rem; font-weight: 800; color: var(--primary); }
    .card-header p { font-size: 0.85rem; color: var(--text-muted); }

    .chart-box { height: 320px; }
    .activity-list { list-style: none; display: flex; flex-direction: column; gap: 16px; margin-bottom: 20px; }
    .activity-item { display: flex; align-items: center; gap: 12px; }
    .activity-avatar { width: 34px; height: 34px; background: var(--bg); border: 1px solid var(--border); border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 0.75rem; font-weight: 800; color: var(--primary); }
    .activity-data { flex: 1; }
    .activity-name { display: block; font-size: 0.85rem; font-weight: 700; color: var(--text-main); }
    .activity-desc { font-size: 0.75rem; color: var(--text-muted); }
    .activity-amt { font-size: 0.85rem; font-weight: 800; color: var(--primary); }
    .stock-pill { min-width: 54px; text-align: center; padding: 6px 10px; border-radius: 999px; background: #dcfce7; color: #166534; font-size: 0.75rem; font-weight: 800; }
    .stock-pill.low { background: #fee2e2; color: #b91c1c; }

    .view-all { display: block; font-size: 0.8rem; font-weight: 700; color: var(--accent); text-decoration: none; text-align: center; padding: 12px; border: 1px solid var(--border); border-radius: 10px; transition: var(--transition); }
    .view-all:hover { background: var(--bg); }
    .empty-activity { color: var(--text-muted); font-size: 0.85rem; }

    @media (max-width: 1200px) {
        .performance-grid { grid-template-columns: 1fr; }
        .stats-row { grid-template-columns: repeat(2, 1fr); }
    }

    @media (max-width: 768px) {
        .stats-row { grid-template-columns: 1fr; }
    }
</style>
@endpush

@push('scripts')
<script>
    @php
        $refillsByDay = \App\Models\Refill::selectRaw("DAYOFWEEK(refill_date) as day, SUM(amount) as total")
            ->where('refill_date', '>=', \Carbon\Carbon::now()->subDays(7))
            ->groupBy('day')
            ->orderBy('day')
            ->pluck('total', 'day')
            ->toArray();
    @endphp

    const refillsByDay = @json($refillsByDay);
    const chartData = [1, 2, 3, 4, 5, 6, 7].map(day => refillsByDay[day] || 0);

    const ctx = document.getElementById('mainChart').getContext('2d');
    new Chart(ctx, {
        type: 'line',
        data: {
            labels: ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'],
            datasets: [{
                label: 'Daily Revenue (PHP)',
                data: chartData,
                borderColor: '#3b82f6',
                backgroundColor: 'rgba(59, 130, 246, 0.1)',
                fill: true,
                tension: 0.4,
                borderWidth: 3,
                pointRadius: 6,
                pointBackgroundColor: '#3b82f6',
                pointBorderColor: '#fff',
                pointBorderWidth: 2,
                pointHoverRadius: 8,
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: true,
                    labels: {
                        font: { size: 12, weight: 'bold' },
                        color: '#64748b',
                        usePointStyle: true,
                        padding: 20
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    grid: { color: '#f1f5f9', drawBorder: false },
                    ticks: {
                        font: { size: 11 },
                        color: '#94a3b8',
                        callback: (value) => 'PHP ' + value.toLocaleString()
                    }
                },
                x: {
                    grid: { display: false, drawBorder: false },
                    ticks: {
                        font: { size: 11, weight: 'bold' },
                        color: '#64748b'
                    }
                }
            }
        }
    });
</script>
@endpush
@endsection
