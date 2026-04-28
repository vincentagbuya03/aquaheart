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
    <div class="card stat-card pos-primary">
        <div class="stat-top">
            <span class="stat-label">Shift Transactions</span>
            <div class="stat-icon"><i data-lucide="shopping-cart"></i></div>
        </div>
        <div class="stat-value">{{ number_format($todayTransactions) }}</div>
        <div class="stat-footer">Items processed today</div>
    </div>
    <div class="card stat-card pos-success">
        <div class="stat-top">
            <span class="stat-label">Shift Revenue</span>
            <div class="stat-icon"><i data-lucide="banknote"></i></div>
        </div>
        <div class="stat-value">₱{{ number_format($todayRevenue, 2) }}</div>
        <div class="stat-footer">Gross collections today</div>
    </div>
    <div class="card stat-card pos-accent">
        <div class="stat-top">
            <span class="stat-label">Total Performance</span>
            <div class="stat-icon"><i data-lucide="award"></i></div>
        </div>
        <div class="stat-value">₱{{ number_format($totalRevenue, 2) }}</div>
        <div class="stat-footer">Career total sales generated</div>
    </div>
</div>

<div class="dashboard-main-grid">
    <div class="main-content-area">
        <div class="card logs-card">
            <div class="card-header-flex">
                <div>
                    <h3>Your Recent Transactions</h3>
                    <p>Last 5 sales you processed</p>
                </div>
                <a href="{{ route('aquaheart.refills.index') }}" class="btn-link">View All <i data-lucide="arrow-right" size="14"></i></a>
            </div>
            
            <div class="pos-table-container">
                <table class="pos-table">
                    <thead>
                        <tr>
                            <th>Receipt</th>
                            <th>Customer</th>
                            <th>Total</th>
                            <th>Status</th>
                            <th>Time</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($recentTransactions as $refill)
                            <tr onclick="window.location='{{ route('aquaheart.refills.show', $refill) }}'" style="cursor: pointer;">
                                <td class="receipt-cell">
                                    <span class="receipt-no">{{ $refill->receipt_number }}</span>
                                    <span class="item-name">{{ $refill->product->name ?? 'Service' }}</span>
                                </td>
                                <td class="customer-cell">
                                    <div class="cust-avatar">{{ substr($refill->customer->name ?? 'W', 0, 1) }}</div>
                                    <span>{{ $refill->customer->name ?? 'Walk-in' }}</span>
                                </td>
                                <td class="amount-cell">₱{{ number_format(($refill->quantity ?? 0) * ($refill->unit_price ?? 0), 2) }}</td>
                                <td>
                                    @php $status = $refill->payment_status ?? 'paid'; @endphp
                                    <span class="status-dot {{ $status }}"></span>
                                    <span class="status-text">{{ ucfirst($status) }}</span>
                                </td>
                                <td class="time-cell">{{ $refill->created_at->diffForHumans() }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="empty-row">No transactions recorded in this shift yet.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div class="charts-grid">
            <div class="card chart-card">
                <div class="card-header">
                    <h3>Shift Productivity</h3>
                    <p>Revenue across last 30 days</p>
                </div>
                <div class="chart-container">
                    <canvas id="salesChart"></canvas>
                </div>
            </div>
            <div class="card chart-card">
                <div class="card-header">
                    <h3>Performance History</h3>
                    <p>Monthly sales breakdown</p>
                </div>
                <div class="chart-container">
                    <canvas id="monthlySalesChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    <aside class="dashboard-sidebar">
        <div class="card inventory-summary-card">
            <div class="card-header">
                <h3>Inventory Pulse</h3>
                <p>Monitor stock availability</p>
            </div>
            <ul class="inventory-list">
                @foreach(\App\Models\Product::where('is_active', true)->orderBy('stock_quantity')->take(6)->get() as $product)
                <li class="inventory-item">
                    <div class="inv-info">
                        <span class="inv-name">{{ $product->name }}</span>
                        <div class="inv-progress-bg">
                            @php $perc = min(100, ($product->stock_quantity / ($product->reorder_level * 3 ?: 100)) * 100); @endphp
                            <div class="inv-progress-bar {{ $product->stock_quantity <= $product->reorder_level ? 'critical' : '' }}" style="width: {{ $perc }}%"></div>
                        </div>
                    </div>
                    <span class="inv-count {{ $product->stock_quantity <= $product->reorder_level ? 'low' : '' }}">
                        {{ $product->stock_quantity }}
                    </span>
                </li>
                @endforeach
            </ul>
        </div>

        <div class="card quick-actions-panel">
            <h3>POS shortcuts</h3>
            <div class="shortcut-grid">
                <a href="{{ route('aquaheart.refills.create') }}" class="shortcut-btn">
                    <div class="shortcut-icon"><i data-lucide="plus"></i></div>
                    <span>New Sale</span>
                </a>
                <a href="{{ route('aquaheart.customers.index') }}" class="shortcut-btn">
                    <div class="shortcut-icon"><i data-lucide="users"></i></div>
                    <span>Find User</span>
                </a>
                <a href="{{ route('aquaheart.refills.index') }}" class="shortcut-btn">
                    <div class="shortcut-icon"><i data-lucide="receipt"></i></div>
                    <span>History</span>
                </a>
                <a href="{{ route('aquaheart.support') }}" class="shortcut-btn">
                    <div class="shortcut-icon"><i data-lucide="help-circle"></i></div>
                    <span>Support</span>
                </a>
            </div>
        </div>
    </aside>
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
    .stats-row { display: grid; grid-template-columns: repeat(3, 1fr); gap: 24px; margin-bottom: 24px; }
    .stat-card { padding: 24px; border-radius: 20px; color: white; display: flex; flex-direction: column; gap: 8px; border: none; box-shadow: 0 10px 15px -3px rgba(0,0,0,0.1); }
    .stat-card.pos-primary { background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%); }
    .stat-card.pos-success { background: linear-gradient(135deg, #10b981 0%, #047857 100%); }
    .stat-card.pos-accent { background: linear-gradient(135deg, #8b5cf6 0%, #6d28d9 100%); }
    .stat-top { display: flex; justify-content: space-between; align-items: flex-start; }
    .stat-label { font-size: 0.7rem; font-weight: 800; text-transform: uppercase; opacity: 0.9; letter-spacing: 0.5px; }
    .stat-icon { width: 40px; height: 40px; border-radius: 12px; background: rgba(255,255,255,0.2); display: flex; align-items: center; justify-content: center; backdrop-filter: blur(4px); }
    .stat-value { font-size: 1.8rem; font-weight: 800; letter-spacing: -0.5px; }
    .stat-footer { font-size: 0.75rem; opacity: 0.8; font-weight: 600; }

    .dashboard-main-grid { display: grid; grid-template-columns: 1fr 320px; gap: 24px; }
    .main-content-area { display: flex; flex-direction: column; gap: 24px; }
    
    .card-header-flex { display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; }
    .card-header-flex h3 { font-size: 1.1rem; font-weight: 800; color: var(--primary); }
    .card-header-flex p { font-size: 0.85rem; color: var(--text-muted); }
    .btn-link { font-size: 0.8rem; font-weight: 700; color: var(--accent); text-decoration: none; display: flex; align-items: center; gap: 6px; }

    .pos-table-container { overflow-x: auto; }
    .pos-table { width: 100%; border-collapse: separate; border-spacing: 0 8px; }
    .pos-table th { text-align: left; padding: 12px; font-size: 0.7rem; color: var(--text-muted); text-transform: uppercase; font-weight: 800; }
    .pos-table td { padding: 16px 12px; background: #f8fafc; font-size: 0.85rem; transition: var(--transition); }
    .pos-table tr td:first-child { border-radius: 12px 0 0 12px; }
    .pos-table tr td:last-child { border-radius: 0 12px 12px 0; }
    .pos-table tr:hover td { background: #f1f5f9; }

    .receipt-cell { display: flex; flex-direction: column; gap: 4px; }
    .receipt-no { font-weight: 800; color: var(--primary); font-size: 0.85rem; }
    .item-name { font-size: 0.75rem; color: var(--text-muted); }

    .customer-cell { display: flex; align-items: center; gap: 10px; font-weight: 700; }
    .cust-avatar { width: 28px; height: 28px; border-radius: 8px; background: #e2e8f0; display: flex; align-items: center; justify-content: center; font-size: 0.7rem; color: var(--primary); }
    
    .amount-cell { font-weight: 800; color: var(--primary); }
    .status-dot { display: inline-block; width: 8px; height: 8px; border-radius: 50%; margin-right: 6px; }
    .status-dot.paid { background: #10b981; box-shadow: 0 0 8px #10b981; }
    .status-dot.unpaid { background: #ef4444; box-shadow: 0 0 8px #ef4444; }
    .status-dot.partial { background: #f59e0b; box-shadow: 0 0 8px #f59e0b; }
    .status-text { font-size: 0.75rem; font-weight: 700; color: var(--text-main); }
    .time-cell { font-size: 0.75rem; color: var(--text-muted); text-align: right; }

    .charts-grid { display: grid; grid-template-columns: repeat(2, 1fr); gap: 24px; }
    .chart-container { position: relative; height: 260px; margin-top: 16px; }

    .inventory-list { list-style: none; display: flex; flex-direction: column; gap: 18px; margin-top: 20px; }
    .inventory-item { display: flex; align-items: center; gap: 12px; }
    .inv-info { flex: 1; }
    .inv-name { font-size: 0.8rem; font-weight: 700; color: var(--text-main); display: block; margin-bottom: 6px; }
    .inv-progress-bg { height: 6px; background: #f1f5f9; border-radius: 3px; overflow: hidden; }
    .inv-progress-bar { height: 100%; background: #3b82f6; border-radius: 3px; }
    .inv-progress-bar.critical { background: #ef4444; }
    .inv-count { min-width: 36px; text-align: right; font-size: 0.85rem; font-weight: 800; color: var(--primary); }
    .inv-count.low { color: #ef4444; }

    .shortcut-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 12px; margin-top: 16px; }
    .shortcut-btn { display: flex; flex-direction: column; align-items: center; gap: 10px; padding: 16px; background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 16px; text-decoration: none; transition: var(--transition); }
    .shortcut-btn:hover { background: white; border-color: var(--accent); transform: translateY(-2px); box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05); }
    .shortcut-icon { width: 40px; height: 40px; border-radius: 12px; background: white; display: flex; align-items: center; justify-content: center; color: var(--accent); border: 1px solid #e2e8f0; }
    .shortcut-btn span { font-size: 0.75rem; font-weight: 700; color: var(--text-main); }

    @media (max-width: 1200px) {
        .dashboard-main-grid { grid-template-columns: 1fr; }
        .dashboard-sidebar { display: grid; grid-template-columns: 1fr 1fr; gap: 24px; }
    }

    @media (max-width: 768px) {
        .stats-row { grid-template-columns: 1fr; }
        .charts-grid { grid-template-columns: 1fr; }
        .dashboard-sidebar { grid-template-columns: 1fr; }
    }
</style>
@endpush

@endsection
