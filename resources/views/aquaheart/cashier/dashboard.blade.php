@extends('layouts.aquaheart')

@section('title', 'Cashier Dashboard')
@section('page_title', 'AquaHeart POS')
@section('page_subtitle', 'Quick access to refills, customer lookups, and recent sales.')

@section('page_actions')
<div style="display: flex; gap: 12px;">
    <a href="{{ route('aquaheart.refills.create') }}" class="btn-primary">
        <i data-lucide="plus" size="18"></i>
        New Transaction
    </a>
    <a href="{{ route('aquaheart.customers.index') }}" class="btn-primary" style="background: white; color: var(--primary); border: 1px solid var(--border);">
        <i data-lucide="users" size="18"></i>
        Find Customer
    </a>
</div>
@endsection

@section('content')
@php
    $activeCustomers = \App\Models\Customer::count();
@endphp

<div class="stats-row">
    <div class="card stat-card">
        <div class="stat-top">
            <span class="stat-label">Today's Transactions</span>
            <div class="stat-icon b-blue"><i data-lucide="shopping-cart"></i></div>
        </div>
        <div class="stat-value">{{ $todayTransactions }}</div>
        <div class="stat-footer">Sales processed today</div>
    </div>
    <div class="card stat-card">
        <div class="stat-top">
            <span class="stat-label">Today's Revenue</span>
            <div class="stat-icon b-green"><i data-lucide="banknote"></i></div>
        </div>
        <div class="stat-value">PHP {{ number_format($todayRevenue, 2) }}</div>
        <div class="stat-footer">Gross earnings for today</div>
    </div>
    <div class="card stat-card">
        <div class="stat-top">
            <span class="stat-label">Total Sales (All Time)</span>
            <div class="stat-icon b-purple"><i data-lucide="trending-up"></i></div>
        </div>
        <div class="stat-value">PHP {{ number_format($totalRevenue, 2) }}</div>
        <div class="stat-footer">Total revenue generated</div>
    </div>
</div>

<!-- Sales Charts -->
<div class="charts-grid">
    <div class="card chart-card">
        <div class="card-header">
            <h3>Last 30 Days Sales</h3>
            <p>Daily revenue trend</p>
        </div>
        <div class="chart-container">
            <canvas id="salesChart"></canvas>
        </div>
    </div>

    <div class="card chart-card">
        <div class="card-header">
            <h3>Last 12 Months Sales</h3>
            <p>Monthly revenue comparison</p>
        </div>
        <div class="chart-container">
            <canvas id="monthlySalesChart"></canvas>
        </div>
    </div>
</div>

<div class="performance-grid">
    <div class="card logs-card" style="grid-column: span 2;">
        <div class="card-header">
            <h3>Recent Sales Log</h3>
            <p>Your most recent transactions</p>
        </div>
        <div class="table-container">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Time</th>
                        <th>Customer</th>
                        <th>Type</th>
                        <th>Total</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse(\App\Models\Refill::with('customer')->latest()->take(10)->get() as $refill)
                        <tr>
                            <td class="font-bold">{{ $refill->created_at->format('h:i A') }}</td>
                            <td>{{ $refill->customer->name ?? 'Walk-in' }}</td>
                            <td>
                                <span class="badge {{ $refill->service_type == 'delivery' ? 'badge-blue' : 'badge-green' }}">
                                    {{ ucfirst($refill->service_type ?? 'walk_in') }}
                                </span>
                            </td>
                            <td class="font-bold">PHP {{ number_format(($refill->quantity ?? 0) * ($refill->unit_price ?? 0), 2) }}</td>
                            <td>
                                <a href="{{ route('aquaheart.refills.show', $refill) }}" class="icon-btn" title="View Details">
                                    <i data-lucide="eye" size="16"></i>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" style="text-align: center; padding: 40px; color: var(--text-muted);">
                                No transactions recorded yet today.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <a href="{{ route('aquaheart.refills.index') }}" class="view-all">View Full History -></a>
    </div>

    <div class="card logs-card">
        <div class="card-header">
            <h3>Quick Inventory</h3>
            <p>Product availability</p>
        </div>
        <ul class="activity-list">
            @forelse(\App\Models\Product::orderBy('stock_quantity')->take(8)->get() as $product)
                <li class="activity-item">
                    <div class="activity-avatar">{{ substr($product->name, 0, 1) }}</div>
                    <div class="activity-data">
                        <span class="activity-name">{{ $product->name }}</span>
                        <span class="activity-desc">{{ $product->category ?? 'General' }}</span>
                    </div>
                    <div class="stock-pill {{ $product->stock_quantity <= $product->reorder_level ? 'low' : '' }}">
                        {{ $product->stock_quantity }}
                    </div>
                </li>
            @empty
                <li class="empty-activity">No products in stock.</li>
            @endforelse
        </ul>
        <a href="{{ route('aquaheart.products.index') }}" class="view-all">Check Full Inventory -></a>
    </div>
</div>


@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
    // Daily Sales Chart (Last 30 days)
    const dailySalesCtx = document.getElementById('salesChart');
    if (dailySalesCtx) {
        new Chart(dailySalesCtx, {
            type: 'line',
            data: {
                labels: @json($dailySalesLabels),
                datasets: [{
                    label: 'Daily Revenue (PHP)',
                    data: @json($dailySalesData),
                    borderColor: '#3b82f6',
                    backgroundColor: 'rgba(59, 130, 246, 0.08)',
                    borderWidth: 2,
                    fill: true,
                    tension: 0.4,
                    pointBackgroundColor: '#3b82f6',
                    pointBorderColor: '#fff',
                    pointBorderWidth: 2,
                    pointRadius: 4,
                    pointHoverRadius: 6,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: true,
                        labels: {
                            font: { size: 12, weight: '600' },
                            color: '#64748b',
                            usePointStyle: true,
                            padding: 16,
                        }
                    },
                    filler: {
                        propagate: true
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) {
                                return 'PHP ' + value.toLocaleString();
                            },
                            font: { size: 11 },
                            color: '#94a3b8'
                        },
                        grid: {
                            color: '#e2e8f0',
                            drawBorder: false,
                        }
                    },
                    x: {
                        ticks: {
                            font: { size: 11 },
                            color: '#94a3b8'
                        },
                        grid: {
                            display: false,
                            drawBorder: false,
                        }
                    }
                }
            }
        });
    }

    // Monthly Sales Chart (Last 12 months)
    const monthlySalesCtx = document.getElementById('monthlySalesChart');
    if (monthlySalesCtx) {
        new Chart(monthlySalesCtx, {
            type: 'bar',
            data: {
                labels: @json($monthlySalesLabels),
                datasets: [{
                    label: 'Monthly Revenue (PHP)',
                    data: @json($monthlySalesData),
                    backgroundColor: 'rgba(59, 130, 246, 0.6)',
                    borderColor: '#3b82f6',
                    borderWidth: 1,
                    borderRadius: 6,
                    hoverBackgroundColor: 'rgba(59, 130, 246, 0.8)',
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                indexAxis: undefined,
                plugins: {
                    legend: {
                        display: true,
                        labels: {
                            font: { size: 12, weight: '600' },
                            color: '#64748b',
                            usePointStyle: true,
                            padding: 16,
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) {
                                return 'PHP ' + value.toLocaleString();
                            },
                            font: { size: 11 },
                            color: '#94a3b8'
                        },
                        grid: {
                            color: '#e2e8f0',
                            drawBorder: false,
                        }
                    },
                    x: {
                        ticks: {
                            font: { size: 11 },
                            color: '#94a3b8'
                        },
                        grid: {
                            display: false,
                            drawBorder: false,
                        }
                    }
                }
            }
        });
    }
</script>
@endpush

@push('styles')
<style>
    .stats-row { display: grid; grid-template-columns: repeat(3, 1fr); gap: 24px; margin-bottom: 32px; }
    .stat-card { display: flex; flex-direction: column; gap: 12px; }
    .stat-top { display: flex; justify-content: space-between; align-items: center; }
    .stat-label { font-size: 0.75rem; font-weight: 700; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.5px; }
    .stat-icon { width: 36px; height: 36px; border-radius: 8px; display: flex; align-items: center; justify-content: center; }
    .stat-icon.b-blue { background: #eff6ff; color: #3b82f6; }
    .stat-icon.b-green { background: #f0fdf4; color: #22c55e; }
    .stat-icon.b-orange { background: #fff7ed; color: #f97316; }
    .stat-icon.b-purple { background: #faf5ff; color: #a855f7; }
    .stat-value { font-size: 1.5rem; font-weight: 800; color: var(--primary); letter-spacing: -0.5px; }
    .stat-footer { font-size: 0.8rem; color: var(--text-muted); }

    .charts-grid { display: grid; grid-template-columns: repeat(2, 1fr); gap: 24px; margin-bottom: 32px; }
    .chart-card { display: flex; flex-direction: column; }
    .chart-container { position: relative; height: 300px; margin-top: 16px; }
    
    .card-header { margin-bottom: 12px; }
    .card-header h3 { font-size: 1.1rem; font-weight: 800; color: var(--primary); }
    .card-header p { font-size: 0.85rem; color: var(--text-muted); }

    .performance-grid { display: grid; grid-template-columns: 2fr 1fr; gap: 24px; }

    .table-container { overflow-x: auto; margin-bottom: 20px; }
    .data-table { width: 100%; border-collapse: collapse; }
    .data-table th { text-align: left; padding: 12px; font-size: 0.75rem; color: var(--text-muted); text-transform: uppercase; border-bottom: 1px solid var(--border); }
    .data-table td { padding: 12px; font-size: 0.85rem; border-bottom: 1px solid var(--border); }
    .font-bold { font-weight: 700; color: var(--primary); }

    .badge { padding: 4px 8px; border-radius: 6px; font-size: 0.7rem; font-weight: 700; text-transform: uppercase; }
    .badge-blue { background: #eff6ff; color: #3b82f6; }
    .badge-green { background: #f0fdf4; color: #22c55e; }

    .activity-list { list-style: none; display: flex; flex-direction: column; gap: 16px; margin-bottom: 20px; }
    .activity-item { display: flex; align-items: center; gap: 12px; }
    .activity-avatar { width: 34px; height: 34px; background: var(--bg); border: 1px solid var(--border); border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 0.75rem; font-weight: 800; color: var(--primary); }
    .activity-data { flex: 1; }
    .activity-name { display: block; font-size: 0.85rem; font-weight: 700; color: var(--text-main); }
    .activity-desc { font-size: 0.75rem; color: var(--text-muted); }
    .stock-pill { min-width: 54px; text-align: center; padding: 6px 10px; border-radius: 999px; background: #dcfce7; color: #166534; font-size: 0.75rem; font-weight: 800; }
    .stock-pill.low { background: #fee2e2; color: #b91c1c; }

    .view-all { display: block; font-size: 0.8rem; font-weight: 700; color: var(--accent); text-decoration: none; text-align: center; padding: 12px; border: 1px solid var(--border); border-radius: 10px; transition: var(--transition); }
    .view-all:hover { background: var(--bg); }
    .empty-activity { color: var(--text-muted); font-size: 0.85rem; }

    .icon-btn { display: inline-flex; align-items: center; justify-content: center; width: 32px; height: 32px; border-radius: 6px; background: #f8fafc; color: #64748b; border: 1px solid #e2e8f0; transition: all 0.2s; }
    .icon-btn:hover { background: #eff6ff; color: #3b82f6; border-color: #bfdbfe; }

    @media (max-width: 1024px) {
        .charts-grid { grid-template-columns: 1fr; }
        .performance-grid { grid-template-columns: 1fr; }
        .stats-row { grid-template-columns: repeat(2, 1fr); }
    }

    @media (max-width: 768px) {
        .stats-row { grid-template-columns: 1fr; }
    }
</style>
@endpush

@endsection
