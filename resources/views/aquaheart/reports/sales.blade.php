@extends('layouts.aquaheart')

@section('title', 'Sales Monitor')

@section('content')
@php
    $lowStockCount = $lowStockProducts->count();
@endphp

<div class="sales-monitor-header">
    <div class="title-section">
        <div class="live-status">
            <span class="status-dot"></span>
            LIVE STATION STATUS
        </div>
        <h1 class="sales-title">Sales<span>Monitor</span></h1>
    </div>
    
    <div class="header-metrics">
        <div class="header-metric-card">
            <div class="metric-icon revenue-icon">
                <i data-lucide="banknote"></i>
            </div>
            <div class="metric-info">
                <span class="metric-label">TODAY'S REVENUE</span>
                <div class="metric-value">PHP {{ number_format($todayRevenue, 2) }}</div>
            </div>
        </div>
        
        <div class="header-metric-card">
            <div class="metric-icon volume-icon">
                <i data-lucide="container"></i>
            </div>
            <div class="metric-info">
                <span class="metric-label">VOLUME SOLD</span>
                <div class="metric-value">{{ number_format($todayVolume) }} Gal</div>
            </div>
        </div>
    </div>
</div>

<div class="filter-bar">
    <div class="logs-toolbar">
        <div class="logs-search">
            <i data-lucide="search" size="16"></i>
            <input id="logsSearchInput" type="text" placeholder="Search by transaction or customer...">
        </div>
        <div class="logs-status-chips" role="tablist" aria-label="Log status filter">
            <button type="button" class="log-chip active" data-status="all">All</button>
            <button type="button" class="log-chip" data-status="completed">Completed</button>
            <button type="button" class="log-chip" data-status="pending">Pending</button>
        </div>
    </div>
    <div class="filter-actions">
        <button class="icon-btn" id="logsResetBtn" title="Reset filters"><i data-lucide="sliders-horizontal"></i></button>
        <a href="{{ route('aquaheart.reports.export-refills') }}" class="icon-btn" title="Export CSV"><i data-lucide="download"></i></a>
    </div>
</div>

<div class="card table-container">
    <div class="table-header">
        <h3 class="table-title">Recent Transactions</h3>
        <div class="live-refresh">
            <i data-lucide="refresh-cw" class="refresh-icon"></i>
            <span>Live Refreshing...</span>
        </div>
    </div>
    
    <div class="table-responsive">
        <table class="sales-table">
            <thead>
                <tr>
                    <th>TRANSACTION ID</th>
                    <th>CUSTOMER NAME</th>
                    <th>QUANTITY</th>
                    <th>TOTAL PRICE</th>
                    <th>STATUS</th>
                </tr>
            </thead>
            <tbody>
                @foreach($recentTransactions as $transaction)
                @php
                    $txnStatus = strtolower($transaction->payment_status ?? 'completed');
                    $txnCustomer = strtolower($transaction->customer->name ?? 'guest');
                    $txnRef = strtolower(substr($transaction->id, 0, 5));
                @endphp
                <tr>
                    <td class="id-cell" data-log-status="{{ $txnStatus }}" data-log-customer="{{ $txnCustomer }}" data-log-ref="{{ $txnRef }}">#AH-{{ substr($transaction->id, 0, 5) }}</td>
                    <td>
                        <div class="customer-cell">
                            <div class="customer-avatar">{{ substr($transaction->customer->name ?? '?', 0, 1) }}{{ substr(explode(' ', $transaction->customer->name ?? ' ')[1] ?? '', 0, 1) }}</div>
                            <span class="customer-name">{{ $transaction->customer->name ?? 'Guest' }}</span>
                        </div>
                    </td>
                    <td class="quantity-cell">{{ number_format($transaction->quantity, 1) }} Gal</td>
                    <td class="price-cell">PHP {{ number_format($transaction->quantity * $transaction->unit_price, 2) }}</td>
                    <td>
                        <span class="status-pill {{ strtolower($transaction->payment_status ?? 'completed') }}">
                            {{ strtoupper($transaction->payment_status ?? 'COMPLETED') }}
                        </span>
                    </td>
                </tr>
                @endforeach
                <tr id="logsEmptyState" style="display: none;">
                    <td colspan="5" class="logs-empty-state">No transactions match your filters.</td>
                </tr>
            </tbody>
        </table>
    </div>
    
    <div class="table-footer">
        <div class="showing-text">Showing {{ $recentTransactions->count() }} of {{ $todayRefills }} today's transactions</div>
        <div class="pagination-group">
            {{ $recentTransactions->links() }}
        </div>
    </div>
</div>

@push('styles')
<style>
    /* Hide Default Header from layout */
    .section-header { display: none !important; }

    .sales-monitor-header { display: flex; justify-content: space-between; align-items: flex-end; margin-bottom: 32px; }
    
    .live-status { display: flex; align-items: center; gap: 8px; font-size: 0.7rem; font-weight: 800; color: #0284c7; text-transform: uppercase; letter-spacing: 1px; margin-bottom: 8px; }
    .status-dot { width: 8px; height: 8px; border-radius: 50%; background: #10b981; box-shadow: 0 0 10px rgba(16, 185, 129, 0.5); }
    
    .sales-title { font-size: 2.2rem; font-weight: 800; color: var(--primary); letter-spacing: -1px; }
    .sales-title span { color: #0284c7; }
    
    .header-metrics { display: flex; gap: 16px; }
    .header-metric-card { background: white; padding: 12px 24px; border-radius: 16px; display: flex; align-items: center; gap: 16px; box-shadow: 0 4px 15px rgba(0,0,0,0.02); }
    .metric-icon { width: 44px; height: 44px; border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 1.2rem; }
    .revenue-icon { background: #e0f2fe; color: #0284c7; }
    .volume-icon { background: #ecfdf5; color: #059669; }
    .metric-label { display: block; font-size: 0.65rem; font-weight: 800; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 2px; }
    .metric-value { font-size: 1.15rem; font-weight: 800; color: var(--primary); }

    .filter-bar { background: white; padding: 14px 20px; border-radius: 20px; display: flex; align-items: center; gap: 18px; margin-bottom: 32px; box-shadow: 0 4px 15px rgba(0,0,0,0.02); border: 1px solid #eef2f7; }
    .logs-toolbar { display: flex; align-items: center; gap: 14px; flex-wrap: wrap; }
    .logs-search { display: flex; align-items: center; gap: 10px; background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 12px; padding: 10px 12px; min-width: 320px; }
    .logs-search i { color: #94a3b8; }
    .logs-search input { border: none; background: transparent; width: 100%; font: inherit; color: var(--primary); outline: none; }
    .logs-search input::placeholder { color: #94a3b8; }
    .logs-status-chips { display: flex; gap: 8px; }
    .log-chip { border: 1px solid #e2e8f0; background: #f8fafc; padding: 8px 14px; border-radius: 999px; font-size: 0.78rem; font-weight: 700; color: #64748b; cursor: pointer; transition: all 0.2s ease; }
    .log-chip:hover { border-color: #93c5fd; color: #0f172a; }
    .log-chip.active { background: #0f172a; border-color: #0f172a; color: #ffffff; }
    
    .filter-actions { margin-left: auto; display: flex; gap: 12px; }
    .icon-btn { width: 40px; height: 40px; border-radius: 12px; border: 1px solid #e2e8f0; background: white; display: flex; align-items: center; justify-content: center; color: #64748b; cursor: pointer; transition: all 0.2s; }
    .icon-btn:hover { background: #f1f5f9; color: var(--primary); }

    .table-container { padding: 0; border-radius: 24px; overflow: hidden; margin-bottom: 32px; box-shadow: 0 10px 30px rgba(0,0,0,0.02); }
    .table-header { padding: 24px 32px; border-bottom: 1px solid var(--border); display: flex; justify-content: space-between; align-items: center; }
    .table-title { font-size: 1.2rem; font-weight: 800; color: var(--primary); }
    .live-refresh { display: flex; align-items: center; gap: 8px; font-size: 0.75rem; font-weight: 700; color: #0284c7; }
    .refresh-icon { width: 14px; height: 14px; animation: rotate 2s linear infinite; }
    @keyframes rotate { from { transform: rotate(0deg); } to { transform: rotate(360deg); } }
    
    .sales-table { width: 100%; border-collapse: collapse; }
    .sales-table th { padding: 16px 32px; text-align: left; font-size: 0.65rem; font-weight: 800; color: var(--text-muted); text-transform: uppercase; letter-spacing: 1px; border-bottom: 1px solid var(--border); background: #fcfdfe; }
    .sales-table td { padding: 20px 32px; border-bottom: 1px solid var(--bg); vertical-align: middle; }
    
    .id-cell { font-family: monospace; font-size: 0.9rem; font-weight: 600; color: #94a3b8; }
    .customer-cell { display: flex; align-items: center; gap: 12px; }
    .customer-avatar { width: 34px; height: 34px; border-radius: 10px; background: #e0f2fe; color: #0369a1; display: flex; align-items: center; justify-content: center; font-size: 0.75rem; font-weight: 800; }
    .customer-name { font-size: 0.95rem; font-weight: 700; color: var(--primary); }
    
    .quantity-cell { font-weight: 700; color: var(--text-main); font-size: 0.9rem; }
    .price-cell { font-weight: 800; color: var(--primary); font-size: 1rem; }
    .status-pill { padding: 4px 12px; border-radius: 8px; font-size: 0.65rem; font-weight: 800; }
    .status-pill.completed { background: #dcfce7; color: #166534; }
    .status-pill.pending { background: #fef3c7; color: #b45309; }
    .logs-empty-state { text-align: center; color: #64748b; font-weight: 600; padding: 28px 16px; }
    
    .table-footer { padding: 20px 32px; display: flex; justify-content: space-between; align-items: center; }
    .showing-text { font-size: 0.8rem; color: var(--text-muted); }



    @media (max-width: 1024px) {
        .sales-monitor-header { flex-direction: column; align-items: flex-start; gap: 20px; }
        .filter-bar { flex-direction: column; align-items: stretch; gap: 16px; }
        .logs-toolbar { width: 100%; }
        .logs-search { min-width: 0; width: 100%; }
        .filter-actions { margin-left: 0; width: 100%; justify-content: flex-end; }
        .insights-row { grid-template-columns: 1fr; }
    }
</style>
@endpush

@push('scripts')
<script>
    (() => {
        const searchInput = document.getElementById('logsSearchInput');
        const chips = Array.from(document.querySelectorAll('.log-chip'));
        const resetBtn = document.getElementById('logsResetBtn');
        const rows = Array.from(document.querySelectorAll('.sales-table tbody tr')).filter((row) => row.id !== 'logsEmptyState');
        const emptyState = document.getElementById('logsEmptyState');

        if (!searchInput || chips.length === 0 || rows.length === 0) {
            return;
        }

        let activeStatus = 'all';

        const applyFilters = () => {
            const query = searchInput.value.trim().toLowerCase();
            let visibleCount = 0;

            rows.forEach((row) => {
                const statusCell = row.querySelector('[data-log-status]');
                const status = statusCell?.dataset.logStatus || '';
                const customer = statusCell?.dataset.logCustomer || '';
                const ref = statusCell?.dataset.logRef || '';
                const statusMatch = activeStatus === 'all' || status === activeStatus;
                const textMatch = query === '' || customer.includes(query) || ref.includes(query);
                const shouldShow = statusMatch && textMatch;

                row.style.display = shouldShow ? '' : 'none';
                if (shouldShow) {
                    visibleCount += 1;
                }
            });

            if (emptyState) {
                emptyState.style.display = visibleCount === 0 ? '' : 'none';
            }
        };

        chips.forEach((chip) => {
            chip.addEventListener('click', () => {
                chips.forEach((btn) => btn.classList.remove('active'));
                chip.classList.add('active');
                activeStatus = chip.dataset.status || 'all';
                applyFilters();
            });
        });

        searchInput.addEventListener('input', applyFilters);

        resetBtn?.addEventListener('click', () => {
            activeStatus = 'all';
            chips.forEach((btn) => btn.classList.toggle('active', btn.dataset.status === 'all'));
            searchInput.value = '';
            applyFilters();
        });
    })();
</script>
@endpush
@endsection
