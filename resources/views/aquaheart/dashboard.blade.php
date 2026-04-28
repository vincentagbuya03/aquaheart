@extends('layouts.aquaheart')

@section('title', 'Dashboard')

@section('content')

<div class="dashboard-header">
    <h1 class="welcome-text">Good morning, {{ explode(' ', auth()->user()->name)[0] }}.</h1>
    <p class="welcome-sub">Your hydration ecosystem is operating at peak efficiency today.</p>
</div>

<div class="metrics-grid">
    <div class="card metric-card sales-metric">
        <div class="metric-info">
            <span class="metric-label">TOTAL SALES TODAY</span>
            <div class="metric-value-row">
                <span class="metric-value">₱{{ number_format($todaySales, 2) }}</span>
            </div>
            <div class="metric-trend {{ $salesTrendPercent >= 0 ? 'trend-up' : 'trend-down' }}">
                <i data-lucide="{{ $salesTrendPercent >= 0 ? 'trending-up' : 'trending-down' }}"></i>
                <span>{{ abs(round($salesTrendPercent, 1)) }}% from yesterday</span>
            </div>
        </div>
        <div class="metric-bg-icon">
            <i data-lucide="bar-chart-3"></i>
        </div>
    </div>

    <div class="card metric-card refills-metric">
        <div class="metric-info">
            <span class="metric-label">NUMBER OF REFILLS</span>
            <div class="metric-value-row">
                <span class="metric-value">{{ $todayRefills }}</span>
            </div>
            <div class="metric-detail">
                <i data-lucide="droplet" class="detail-icon"></i>
                <span>{{ number_format($todayLiters) }} Liters dispensed</span>
            </div>
        </div>
        <div class="metric-bg-icon">
            <i data-lucide="container"></i>
        </div>
    </div>

    <div class="card metric-card deliveries-metric">
        <div class="metric-info">
            <span class="metric-label">ACTIVE DELIVERIES</span>
            <div class="metric-value-row">
                <span class="metric-value">{{ $activeDeliveries }}</span>
            </div>
            <div class="metric-detail">
                <i data-lucide="truck" class="detail-icon"></i>
                <span>{{ $pendingDispatch }} pending dispatch</span>
            </div>
        </div>
        <div class="metric-bg-icon">
            <i data-lucide="package"></i>
        </div>
    </div>
</div>

<div class="dashboard-middle-grid single-col">
    <div class="card chart-card">
        <div class="chart-header">
            <div class="chart-title-group">
                <h3 class="chart-title">Sales Trend</h3>
                <p class="chart-subtitle">Hourly performance overview</p>
            </div>
            <div class="chart-toggles">
                <button class="toggle-btn">Daily</button>
                <button class="toggle-btn active">Weekly</button>
            </div>
        </div>
        <div class="chart-container">
            <canvas id="salesTrendChart"></canvas>
        </div>
    </div>
</div>

<div class="transactions-section">
    <div class="transactions-header">
        <h3 class="transactions-title">Recent Transactions</h3>
        <p class="transactions-subtitle">Real-time purchase activity across all stations</p>
        <a href="{{ route('aquaheart.refills.index') }}" class="view-all-link">View All</a>
    </div>

    <div class="card table-card">
        <table class="dashboard-table">
            <thead>
                <tr>
                    <th>CUSTOMER</th>
                    <th>STATION</th>
                    <th>VOLUME</th>
                    <th>STATUS</th>
                    <th class="text-right">TOTAL</th>
                </tr>
            </thead>
            <tbody>
                @foreach($recentTransactions as $transaction)
                <tr>
                    <td>
                        <div class="customer-cell">
                            <div class="customer-avatar">
                                {{ substr($transaction->customer->name ?? '?', 0, 1) }}{{ substr(explode(' ', $transaction->customer->name ?? ' ')[1] ?? '', 0, 1) }}
                            </div>
                            <div class="customer-info">
                                <span class="customer-name">{{ $transaction->customer->name ?? 'Guest' }}</span>
                                <span class="customer-id">ID: #AQ-{{ substr($transaction->id, 0, 4) }}</span>
                            </div>
                        </div>
                    </td>
                    <td class="station-cell">
                        {{ $transaction->user->name ?? 'Main Hub Purifier' }}
                    </td>
                    <td class="volume-cell">
                        {{ number_format($transaction->quantity * 20, 1) }} L
                    </td>
                    <td>
                        <span class="status-pill {{ strtolower($transaction->payment_status ?? 'completed') }}">
                            {{ strtoupper($transaction->payment_status ?? 'COMPLETED') }}
                        </span>
                    </td>
                    <td class="text-right total-cell">
                        ₱{{ number_format($transaction->quantity * $transaction->unit_price, 2) }}
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<a href="{{ route('aquaheart.refills.create') }}" class="floating-action-btn" title="New Transaction">
    <i data-lucide="plus"></i>
</a>

@push('styles')
<style>
    /* Hide Default Header from layout */
    .section-header { display: none !important; }

    .dashboard-header { margin-bottom: 32px; }
    .welcome-text { font-size: 2.2rem; font-weight: 800; color: var(--primary); letter-spacing: -1px; margin-bottom: 4px; }
    .welcome-sub { color: var(--text-muted); font-size: 1rem; }

    .metrics-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 24px; margin-bottom: 32px; }
    .metric-card { position: relative; padding: 28px; border-radius: 20px; border: none; box-shadow: 0 10px 30px rgba(0,0,0,0.02); overflow: hidden; }
    .metric-label { font-size: 0.7rem; font-weight: 800; color: #0284c7; text-transform: uppercase; letter-spacing: 1px; display: block; margin-bottom: 12px; }
    .metric-value { font-size: 2.4rem; font-weight: 800; color: var(--primary); letter-spacing: -1px; }
    .metric-trend { display: flex; align-items: center; gap: 6px; font-size: 0.85rem; font-weight: 700; margin-top: 8px; }
    .trend-up { color: #10b981; }
    .trend-down { color: #ef4444; }
    .metric-detail { display: flex; align-items: center; gap: 8px; font-size: 0.85rem; font-weight: 700; color: var(--text-muted); margin-top: 12px; }
    .detail-icon { color: #0284c7; width: 16px; height: 16px; }
    .metric-bg-icon { position: absolute; right: -10px; bottom: -10px; font-size: 5rem; color: #f1f5f9; z-index: 0; opacity: 0.5; }
    .metric-bg-icon i { width: 80px; height: 80px; }
    .metric-info { position: relative; z-index: 1; }

    .deliveries-metric { background: #0284c7; }
    .deliveries-metric .metric-label, .deliveries-metric .metric-value, .deliveries-metric .metric-detail { color: white; }
    .deliveries-metric .detail-icon { color: #e0f2fe; }
    .deliveries-metric .metric-bg-icon { color: rgba(255,255,255,0.1); }

    .dashboard-middle-grid.single-col { grid-template-columns: 1fr; }
    .chart-card { padding: 32px; border-radius: 24px; }
    .chart-header { display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 32px; }
    .chart-title { font-size: 1.25rem; font-weight: 800; color: var(--primary); margin-bottom: 4px; }
    .chart-subtitle { font-size: 0.85rem; color: var(--text-muted); }
    .chart-toggles { display: flex; background: #f1f5f9; padding: 4px; border-radius: 10px; }
    .toggle-btn { border: none; background: transparent; padding: 6px 16px; font-size: 0.75rem; font-weight: 700; color: var(--text-muted); cursor: pointer; border-radius: 8px; transition: all 0.2s; }
    .toggle-btn.active { background: #0284c7; color: white; box-shadow: 0 4px 10px rgba(2, 132, 199, 0.2); }
    .chart-container { height: 300px; }

    .health-card { padding: 32px; border-radius: 24px; }
    .health-title { font-size: 1.25rem; font-weight: 800; color: var(--primary); margin-bottom: 24px; }
    .health-content { display: flex; align-items: center; gap: 24px; }
    .reservoir-visual { flex-shrink: 0; }
    .reservoir-container { width: 60px; height: 120px; background: #f1f5f9; border-radius: 12px; position: relative; overflow: hidden; }
    .reservoir-fill { position: absolute; bottom: 0; left: 0; right: 0; background: linear-gradient(180deg, #38bdf8 0%, #0284c7 100%); border-radius: 0 0 12px 12px; }
    .health-info { flex: 1; }
    .health-label { font-size: 0.75rem; font-weight: 800; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.5px; }
    .health-value { font-size: 1.8rem; font-weight: 800; color: var(--primary); margin: 4px 0; }
    .health-desc { font-size: 0.85rem; color: var(--text-muted); line-height: 1.5; margin-bottom: 16px; }
    .health-status-badge { display: inline-flex; align-items: center; gap: 6px; padding: 6px 12px; background: #ecfdf5; color: #059669; border-radius: 10px; font-size: 0.7rem; font-weight: 800; }
    .health-status-badge.healthy { background: #ecfdf5; color: #059669; }
    .health-status-badge.warning { background: #fef3c7; color: #d97706; }

    .transactions-section { margin-top: 40px; }
    .transactions-header { position: relative; margin-bottom: 24px; padding-right: 100px; }
    .transactions-title { font-size: 1.4rem; font-weight: 800; color: var(--primary); margin-bottom: 4px; }
    .transactions-subtitle { font-size: 0.9rem; color: var(--text-muted); }
    .view-all-link { position: absolute; right: 0; top: 10px; font-size: 0.85rem; font-weight: 700; color: #0284c7; text-decoration: none; }

    .dashboard-table { width: 100%; border-collapse: collapse; }
    .dashboard-table th { padding: 16px 24px; text-align: left; font-size: 0.65rem; font-weight: 800; color: var(--text-muted); text-transform: uppercase; letter-spacing: 1px; border-bottom: 1px solid var(--border); }
    .dashboard-table td { padding: 16px 24px; border-bottom: 1px solid var(--border); vertical-align: middle; }
    
    .customer-cell { display: flex; align-items: center; gap: 12px; }
    .customer-avatar { width: 36px; height: 36px; background: #e0f2fe; color: #0369a1; border-radius: 10px; display: flex; align-items: center; justify-content: center; font-size: 0.75rem; font-weight: 800; }
    .customer-name { display: block; font-size: 0.9rem; font-weight: 700; color: var(--primary); }
    .customer-id { font-size: 0.75rem; color: var(--text-muted); }
    
    .station-cell, .volume-cell { font-size: 0.9rem; font-weight: 600; color: var(--text-main); }
    .status-pill { padding: 4px 12px; border-radius: 8px; font-size: 0.65rem; font-weight: 800; }
    .status-pill.completed { background: #e0f2fe; color: #0369a1; }
    .status-pill.pending { background: #f1f5f9; color: #64748b; }
    .total-cell { font-size: 1rem; font-weight: 800; color: var(--primary); }

    .floating-action-btn { position: fixed; right: 40px; bottom: 40px; width: 64px; height: 64px; background: linear-gradient(135deg, #38bdf8 0%, #0284c7 100%); color: white; border-radius: 20px; display: flex; align-items: center; justify-content: center; box-shadow: 0 10px 25px rgba(2, 132, 199, 0.4); text-decoration: none; transition: all 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275); z-index: 100; }
    .floating-action-btn:hover { transform: scale(1.1) rotate(5deg); box-shadow: 0 15px 35px rgba(2, 132, 199, 0.5); }
    .floating-action-btn i { width: 28px; height: 28px; }

    @media (max-width: 1024px) {
        .metrics-grid { grid-template-columns: 1fr; }
        .dashboard-middle-grid { grid-template-columns: 1fr; }
    }
</style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const ctx = document.getElementById('salesTrendChart').getContext('2d');
        
        // Gradient for bars
        const gradient = ctx.createLinearGradient(0, 0, 0, 300);
        gradient.addColorStop(0, '#38bdf8');
        gradient.addColorStop(1, '#0284c7');

        const salesTrendChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: @json($chartLabels),
                datasets: [{
                    label: 'Sales (₱)',
                    data: @json($chartValues),
                    backgroundColor: @json($chartColors),
                    borderRadius: 8,
                    borderSkipped: false,
                    barThickness: 32,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: { color: '#f1f5f9', drawBorder: false },
                        ticks: { color: '#94a3b8', font: { size: 11 } }
                    },
                    x: {
                        grid: { display: false, drawBorder: false },
                        ticks: { color: '#64748b', font: { size: 11, weight: 'bold' } }
                    }
                }
            }
        });
    });
</script>
@endpush
@endsection
