@extends('layouts.aquaheart')

@section('title', 'Sales Performance')
@section('page_title', 'Revenue and Inventory Reports')
@section('page_subtitle', 'Monitor station performance, payment collection, delivery volume, and stock alerts.')

@section('page_actions')
<div style="display: flex; gap: 10px;">
    <a href="{{ route('aquaheart.reports.export-refills') }}" class="btn-primary" style="background: white; border: 1px solid var(--border); color: var(--primary);">
        <i data-lucide="file-text" size="18"></i>
        Export Data
    </a>
    <a href="{{ route('aquaheart.reports.print-refills') }}" target="_blank" class="btn-primary">
        <i data-lucide="printer" size="18"></i>
        Print View
    </a>
</div>
@endsection

@section('content')
<div class="stats-row">
    <div class="card stat-card">
        <div class="stat-meta">Today's Revenue</div>
        <div class="stat-v">PHP {{ number_format($todayRevenue, 2) }}</div>
        <div class="stat-s">{{ $todayRefills }} transactions today</div>
    </div>
    <div class="card stat-card">
        <div class="stat-meta">Lifetime Revenue</div>
        <div class="stat-v">PHP {{ number_format($totalRevenue, 2) }}</div>
        <div class="stat-s">{{ $totalRefills }} total transactions</div>
    </div>
    <div class="card stat-card">
        <div class="stat-meta">Paid Transactions</div>
        <div class="stat-v">{{ $paidTransactions }}</div>
        <div class="stat-s">Successfully settled sales</div>
    </div>
    <div class="card stat-card">
        <div class="stat-meta">Delivery Orders</div>
        <div class="stat-v">{{ $deliveryTransactions }}</div>
        <div class="stat-s">Transactions tagged as delivery</div>
    </div>
</div>

<div class="reports-grid">
    <div class="card table-card">
        <div class="report-header">
            <h3><i data-lucide="calendar"></i> Daily Performance</h3>
            <p>Last 30 days of transaction data</p>
        </div>

        @if($dailyRevenue->count())
            <table class="report-table">
                <thead>
                    <tr>
                        <th>Date Processed</th>
                        <th>Refills</th>
                        <th>Revenue</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($dailyRevenue as $day)
                        <tr>
                            <td class="t-date">{{ \Carbon\Carbon::parse($day->date)->format('M d, Y') }}</td>
                            <td class="t-cnt">{{ $day->count }}</td>
                            <td class="t-rev">PHP {{ number_format($day->total, 2) }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <div class="blank-report">No transactions found for the current period.</div>
        @endif
    </div>

    <div class="side-reports">
        <div class="card table-card compact-card">
            <div class="report-header">
                <h3><i data-lucide="bar-chart-2"></i> Monthly Summary</h3>
            </div>
            @if($monthlyRevenue->count())
                <table class="report-table compact">
                    <tbody>
                        @foreach($monthlyRevenue as $month)
                            <tr>
                                <td class="t-month">{{ \Carbon\Carbon::createFromDate($month->year, $month->month, 1)->format('F Y') }}</td>
                                <td class="t-rev">PHP {{ number_format($month->total, 2) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <div class="blank-report mini">No monthly data.</div>
            @endif
        </div>

        <div class="card table-card compact-card">
            <div class="report-header">
                <h3><i data-lucide="users"></i> Top Customers</h3>
            </div>
            @if($topCustomers->count())
                <table class="report-table compact">
                    <tbody>
                        @foreach($topCustomers as $customer)
                            <tr>
                                <td class="t-month">{{ $customer->name }}</td>
                                <td class="t-rev">PHP {{ number_format($customer->total_spent ?? 0, 2) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <div class="blank-report mini">No customer rankings.</div>
            @endif
        </div>

        <div class="card table-card compact-card">
            <div class="report-header">
                <h3><i data-lucide="triangle-alert"></i> Low Stock Alerts</h3>
            </div>
            @if($lowStockProducts->count())
                <table class="report-table compact">
                    <tbody>
                        @foreach($lowStockProducts as $product)
                            <tr>
                                <td class="t-month">{{ $product->name }}</td>
                                <td class="t-rev">{{ $product->stock_quantity }} left</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <div class="blank-report mini">All products are above reorder level.</div>
            @endif
        </div>
    </div>
</div>

@push('styles')
<style>
    .stats-row { display: grid; grid-template-columns: repeat(4, 1fr); gap: 24px; margin-bottom: 32px; }
    .stat-meta { font-size: 0.75rem; font-weight: 700; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 8px; }
    .stat-v { font-size: 1.5rem; font-weight: 800; color: var(--primary); }
    .stat-s { font-size: 0.8rem; color: var(--text-muted); margin-top: 4px; }
    .reports-grid { display: grid; grid-template-columns: 1.8fr 1fr; gap: 24px; }
    .side-reports { display: flex; flex-direction: column; gap: 24px; }
    .table-card { padding: 0; }
    .report-header { padding: 24px 28px; border-bottom: 1px solid var(--border); }
    .report-header h3 { font-size: 1rem; font-weight: 800; display: flex; align-items: center; gap: 10px; color: var(--primary); }
    .report-header p { font-size: 0.85rem; color: var(--text-muted); margin-top: 2px; }
    .report-table { width: 100%; border-collapse: collapse; }
    .report-table th { padding: 12px 28px; text-align: left; font-size: 0.75rem; font-weight: 800; color: var(--text-muted); text-transform: uppercase; border-bottom: 1px solid var(--border); background: #fcfdfe; }
    .report-table td { padding: 16px 28px; border-bottom: 1px solid var(--bg); font-size: 0.9rem; font-weight: 600; }
    .t-date, .t-month { color: var(--primary); font-weight: 700; }
    .t-rev { text-align: right; color: var(--accent); font-weight: 800; }
    .t-cnt { text-align: center; }
    .compact td { padding: 12px 28px; }
    .blank-report { padding: 60px 40px; text-align: center; color: var(--text-muted); font-size: 0.85rem; }
    .blank-report.mini { padding: 30px; font-size: 0.8rem; }
    @media (max-width: 1200px) { .reports-grid { grid-template-columns: 1fr; } .stats-row { grid-template-columns: repeat(2, 1fr); } }
    @media (max-width: 768px) { .stats-row { grid-template-columns: 1fr; } }
</style>
@endpush
@endsection
