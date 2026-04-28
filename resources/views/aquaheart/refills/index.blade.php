@extends('layouts.aquaheart')

@section('title', 'Transaction Logs')

@section('content')
<div class="logs-header">
    <div class="header-main">
        <div class="breadcrumb">ADMIN PORTAL &bull; SALES OPERATIONS</div>
        <h1 class="page-title">Transaction<span>History</span></h1>
        <p class="page-subtitle">Comprehensive archive of all refill activities, payments, and service distributions.</p>
    </div>
    <div class="header-actions">
        <a href="{{ route('aquaheart.reports.export-refills') }}" class="btn-outline">
            <i data-lucide="download"></i>
            <span>Export CSV</span>
        </a>
        <a href="{{ route('aquaheart.refills.create') }}" class="btn-premium">
            <i data-lucide="plus"></i>
            <span>New Sale</span>
        </a>
    </div>
</div>

<div class="filter-section">
    <div class="status-filters">
        @foreach ([
            'all' => ['label' => 'ALL TRANSACTIONS', 'icon' => 'list', 'color' => '#64748b'],
            'paid' => ['label' => 'FULLY PAID', 'icon' => 'check-circle', 'color' => '#10b981'],
            'unpaid' => ['label' => 'UNPAID LOGS', 'icon' => 'clock', 'color' => '#ef4444'],
            'partial' => ['label' => 'PARTIAL PAYMENTS', 'icon' => 'pie-chart', 'color' => '#f59e0b'],
        ] as $key => $meta)
            <a href="{{ route('aquaheart.refills.index', $key === 'all' ? [] : ['status' => $key]) }}" 
               class="filter-card {{ ($statusFilter ?? 'all') === $key ? 'active' : '' }}">
                <div class="filter-icon" style="background: {{ $meta['color'] }}15; color: {{ $meta['color'] }};">
                    <i data-lucide="{{ $meta['icon'] }}" size="18"></i>
                </div>
                <div class="filter-info">
                    <span class="filter-label">{{ $meta['label'] }}</span>
                    <span class="filter-count">{{ number_format($paymentStatusCounts[$key] ?? 0) }}</span>
                </div>
                @if(($statusFilter ?? 'all') === $key)
                    <div class="active-dot"></div>
                @endif
            </a>
        @endforeach
    </div>
</div>

<div class="card table-container">
    <div class="table-header-row">
        <h3 class="table-title">Activity Feed</h3>
        <div class="table-tools">
            <div class="search-box">
                <i data-lucide="search" size="16"></i>
                <input type="text" placeholder="Filter by receipt # or customer...">
            </div>
            <button class="tool-btn"><i data-lucide="filter" size="18"></i></button>
        </div>
    </div>

    <div class="table-responsive">
        <table class="premium-table">
            <thead>
                <tr>
                    <th>RECEIPT & ITEM</th>
                    <th>CUSTOMER DETAILS</th>
                    <th>SERVICE TYPE</th>
                    <th>PAYMENT STATUS</th>
                    <th>TOTAL AMOUNT</th>
                    <th class="text-right">ACTIONS</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($refills as $refill)
                @php
                    $status = $refill->computed_payment_status;
                    $isPartial = $status === 'partial';
                    $total = $refill->total_amount;
                    $paid = $refill->paid_amount ?? ($status === 'paid' ? $total : 0);
                    $balance = $refill->partial_amount ?? max(0, $total - $paid);
                @endphp
                <tr>
                    <td>
                        <div class="receipt-cell">
                            <div class="receipt-id">#{{ $refill->receipt_number ?: 'TR-'.str_pad($refill->id, 5, '0', STR_PAD_LEFT) }}</div>
                            <div class="item-name">{{ $refill->product->name ?? 'Unknown Product' }}</div>
                        </div>
                    </td>
                    <td>
                        <div class="customer-cell">
                            <div class="customer-avatar">
                                {{ substr($refill->customer->name ?? '?', 0, 1) }}
                            </div>
                            <div class="customer-info">
                                <span class="c-name">{{ $refill->customer->name ?? 'Walk-in Guest' }}</span>
                                <span class="c-date">{{ optional($refill->refill_date)->format('M d, Y') ?? $refill->created_at->format('M d, Y') }}</span>
                            </div>
                        </div>
                    </td>
                    <td>
                        @php
                            $service = strtolower($refill->service_type ?? 'walk_in');
                            $sIcon = $service === 'delivery' ? 'truck' : 'shopping-bag';
                        @endphp
                        <div class="service-tag {{ $service }}">
                            <i data-lucide="{{ $sIcon }}" size="14"></i>
                            <span>{{ ucfirst(str_replace('_', ' ', $service)) }}</span>
                        </div>
                    </td>
                    <td>
                        <div class="payment-action">
                            @if(auth()->user()->is_admin)
                                <form method="POST" action="{{ route('aquaheart.refills.payment-status.update', $refill) }}" class="status-form">
                                    @csrf
                                    @method('PATCH')
                                    <select name="payment_status" class="status-dropdown {{ $status }}" onchange="this.form.submit()">
                                        <option value="paid" {{ $status === 'paid' ? 'selected' : '' }}>Paid</option>
                                        <option value="unpaid" {{ $status === 'unpaid' ? 'selected' : '' }}>Unpaid</option>
                                        <option value="partial" {{ $status === 'partial' ? 'selected' : '' }}>Partial</option>
                                    </select>
                                </form>
                            @else
                                <span class="status-badge {{ $status }}">{{ ucfirst($status) }}</span>
                            @endif
                        </div>
                    </td>
                    <td>
                        <div class="amount-cell">
                            <div class="total-row">
                                <span class="currency">PHP</span>
                                <span class="value">{{ number_format($refill->total_amount ?? ($refill->quantity * $refill->unit_price), 2) }}</span>
                            </div>
                            
                            @if($isPartial)
                                <div class="financial-breakdown">
                                    <div class="breakdown-item paid">
                                        <span class="label">Paid:</span>
                                        <span class="amt">₱{{ number_format($paid, 2) }}</span>
                                    </div>
                                    <div class="breakdown-item balance">
                                        <span class="label">Bal:</span>
                                        <span class="amt">₱{{ number_format($balance, 2) }}</span>
                                    </div>
                                    <div class="progress-mini">
                                        @php $percent = $total > 0 ? min(100, ($paid / $total) * 100) : 0; @endphp
                                        <div class="progress-fill" style="width: {{ $percent }}%"></div>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </td>
                    <td class="text-right">
                        <div class="action-group">
                            <a href="{{ route('aquaheart.refills.show', $refill) }}" class="act-btn" title="View Details"><i data-lucide="eye"></i></a>
                            
                            @if(auth()->user()->is_admin)
                                <a href="{{ route('aquaheart.refills.edit', $refill) }}" class="act-btn" title="Edit"><i data-lucide="edit-3"></i></a>
                                <form method="POST" action="{{ route('aquaheart.refills.destroy', $refill) }}" style="display: inline;" data-ajax-delete data-delete-label="transaction {{ $refill->receipt_number }}">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="act-btn delete" title="Delete"><i data-lucide="trash-2"></i></button>
                                </form>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="empty-state">
                        <div class="empty-content">
                            <i data-lucide="clipboard-list" size="48"></i>
                            <p>No transactions found matching your criteria.</p>
                            <a href="{{ route('aquaheart.refills.create') }}" class="btn-text">Log your first sale</a>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    @if($refills->hasPages())
    <div class="table-footer">
        <div class="footer-info">Showing {{ $refills->firstItem() }} - {{ $refills->lastItem() }} of {{ $refills->total() }} records</div>
        <div class="footer-pagination">
            {{ $refills->links('pagination::simple-bootstrap-5') }}
        </div>
    </div>
    @endif
</div>

@push('styles')
<style>
    /* Hide Default Header */
    .section-header { display: none !important; }

    .logs-header { display: flex; justify-content: space-between; align-items: flex-end; margin-bottom: 32px; }
    .breadcrumb { font-size: 0.7rem; font-weight: 800; color: #0284c7; text-transform: uppercase; letter-spacing: 1.5px; margin-bottom: 8px; }
    .page-title { font-size: 2.2rem; font-weight: 800; color: var(--primary); letter-spacing: -1.5px; }
    .page-title span { color: #0284c7; }
    .page-subtitle { color: var(--text-muted); font-size: 0.95rem; margin-top: 4px; }

    .header-actions { display: flex; gap: 12px; }
    .btn-premium { background: var(--primary); color: white; padding: 12px 24px; border-radius: 12px; font-weight: 700; text-decoration: none; display: flex; align-items: center; gap: 8px; transition: all 0.2s; box-shadow: 0 4px 12px rgba(15, 23, 42, 0.1); }
    .btn-premium:hover { transform: translateY(-1px); background: #1e293b; }
    .btn-outline { background: white; color: var(--primary); padding: 12px 24px; border-radius: 12px; font-weight: 700; text-decoration: none; display: flex; align-items: center; gap: 8px; border: 1px solid var(--border); transition: all 0.2s; }
    .btn-outline:hover { background: #f8fafc; }

    .status-filters { display: grid; grid-template-columns: repeat(4, 1fr); gap: 20px; margin-bottom: 40px; }
    .filter-card { background: white; padding: 20px; border-radius: 20px; text-decoration: none; display: flex; align-items: center; gap: 16px; border: 1px solid transparent; box-shadow: 0 4px 20px rgba(0,0,0,0.02); transition: all 0.2s; position: relative; }
    .filter-card:hover { transform: translateY(-2px); border-color: #e2e8f0; }
    .filter-card.active { border-color: #0284c7; background: #f0f9ff; }
    .filter-icon { width: 44px; height: 44px; border-radius: 12px; display: flex; align-items: center; justify-content: center; flex-shrink: 0; }
    .filter-info { display: flex; flex-direction: column; }
    .filter-label { font-size: 0.65rem; font-weight: 800; color: var(--text-muted); letter-spacing: 0.5px; }
    .filter-count { font-size: 1.4rem; font-weight: 800; color: var(--primary); }
    .active-dot { width: 6px; height: 6px; background: #0284c7; border-radius: 50%; position: absolute; top: 12px; right: 12px; }

    .table-container { padding: 0; border-radius: 24px; overflow: hidden; box-shadow: 0 10px 30px rgba(0,0,0,0.03); margin-bottom: 40px; }
    .table-header-row { padding: 24px 32px; display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid var(--border); }
    .table-title { font-size: 1.25rem; font-weight: 800; color: var(--primary); }
    .table-tools { display: flex; gap: 12px; align-items: center; }
    .search-box { display: flex; align-items: center; gap: 10px; background: #f1f5f9; padding: 8px 16px; border-radius: 12px; border: 1px solid transparent; }
    .search-box input { border: none; background: transparent; outline: none; font-size: 0.85rem; width: 220px; font-weight: 600; }
    .tool-btn { width: 38px; height: 38px; border-radius: 10px; border: 1px solid var(--border); background: white; color: var(--text-muted); cursor: pointer; }

    .premium-table { width: 100%; border-collapse: collapse; }
    .premium-table th { padding: 18px 32px; text-align: left; font-size: 0.65rem; font-weight: 800; color: var(--text-muted); text-transform: uppercase; letter-spacing: 1px; background: #fcfdfe; border-bottom: 1px solid var(--border); }
    .premium-table td { padding: 20px 32px; border-bottom: 1px solid #f8fafc; vertical-align: middle; }

    .receipt-cell { display: flex; flex-direction: column; gap: 4px; }
    .receipt-id { font-family: monospace; font-size: 0.9rem; font-weight: 700; color: #0284c7; }
    .item-name { font-size: 0.85rem; font-weight: 600; color: var(--text-muted); }

    .customer-cell { display: flex; align-items: center; gap: 14px; }
    .customer-avatar { width: 36px; height: 36px; background: #e0f2fe; color: #0369a1; border-radius: 10px; display: flex; align-items: center; justify-content: center; font-size: 0.85rem; font-weight: 800; }
    .customer-info { display: flex; flex-direction: column; }
    .c-name { font-size: 0.95rem; font-weight: 700; color: var(--primary); }
    .c-date { font-size: 0.75rem; color: var(--text-muted); }

    .service-tag { display: inline-flex; align-items: center; gap: 6px; padding: 6px 14px; border-radius: 10px; font-size: 0.75rem; font-weight: 700; }
    .service-tag.walk_in { background: #f1f5f9; color: #475569; }
    .service-tag.delivery { background: #e0f2fe; color: #0369a1; }

    .status-dropdown { border: none; padding: 6px 12px; border-radius: 8px; font-size: 0.75rem; font-weight: 800; cursor: pointer; outline: none; appearance: none; -webkit-appearance: none; }
    .status-dropdown.paid { background: #dcfce7; color: #166534; }
    .status-dropdown.unpaid { background: #fee2e2; color: #b91c1c; }
    .status-dropdown.partial { background: #fef3c7; color: #b45309; }
    
    .status-badge { padding: 6px 12px; border-radius: 8px; font-size: 0.75rem; font-weight: 800; display: inline-block; }
    .status-badge.paid { background: #dcfce7; color: #166534; }
    .status-badge.unpaid { background: #fee2e2; color: #b91c1c; }
    .status-badge.partial { background: #fef3c7; color: #b45309; }

    .amount-cell { display: flex; flex-direction: column; gap: 6px; }
    .amount-cell .total-row { display: flex; align-items: baseline; gap: 4px; }
    .amount-cell .currency { font-size: 0.7rem; font-weight: 700; color: var(--text-muted); }
    .amount-cell .value { font-size: 1rem; font-weight: 800; color: var(--primary); }

    .financial-breakdown { display: flex; flex-direction: column; gap: 4px; padding: 6px 10px; background: #f8fafc; border-radius: 10px; border: 1px solid #e2e8f0; margin-top: 4px; }
    .breakdown-item { display: flex; justify-content: space-between; align-items: center; gap: 8px; font-size: 0.72rem; }
    .breakdown-item .label { font-weight: 700; color: var(--text-muted); text-transform: uppercase; font-size: 0.65rem; }
    .breakdown-item .amt { font-weight: 800; }
    .breakdown-item.paid .amt { color: #166534; }
    .breakdown-item.balance .amt { color: #b45309; }
    
    .progress-mini { height: 4px; background: #e2e8f0; border-radius: 2px; overflow: hidden; margin-top: 2px; }
    .progress-mini .progress-fill { height: 100%; background: #f59e0b; border-radius: 2px; }

    .action-group { display: flex; gap: 8px; justify-content: flex-end; }
    .act-btn { width: 34px; height: 34px; border-radius: 10px; display: flex; align-items: center; justify-content: center; color: #64748b; background: #f8fafc; text-decoration: none; border: none; cursor: pointer; transition: all 0.2s; }
    .act-btn:hover { background: #e2e8f0; color: var(--primary); }
    .act-btn.delete:hover { background: #fee2e2; color: #ef4444; }

    .table-footer { padding: 24px 32px; background: #fcfdfe; display: flex; justify-content: space-between; align-items: center; }
    .footer-info { font-size: 0.8rem; color: var(--text-muted); font-weight: 600; }
    
    .empty-state { text-align: center; padding: 80px 0; color: var(--text-muted); }
    .empty-content { display: flex; flex-direction: column; align-items: center; gap: 16px; }
    .btn-text { color: #0284c7; text-decoration: none; font-weight: 700; font-size: 0.9rem; }

    .text-right { text-align: right !important; }

    @media (max-width: 1024px) {
        .status-filters { grid-template-columns: repeat(2, 1fr); }
        .logs-header { flex-direction: column; align-items: flex-start; gap: 20px; }
    }
</style>
@endpush
@endsection
