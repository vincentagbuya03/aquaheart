@extends('layouts.aquaheart')

@section('title', 'Customers')
@section('page_title', 'Customer Directory')
@section('page_subtitle', 'Manage household contact details and delivery records.')

@section('page_actions')
<a href="{{ route('aquaheart.customers.create') }}" class="btn-primary">
    <i data-lucide="plus" size="18"></i>
    Add Customer
</a>
@endsection

@section('content')
<!-- Payment Status Summary Card -->
<div class="summary-grid">
    <div class="summary-card summary-unpaid">
        <div class="summary-icon" style="background: #fee2e2; color: #991b1b;">
            <i data-lucide="alert-circle" size="24"></i>
        </div>
        <div class="summary-content">
            <div class="summary-label">Unpaid Customers</div>
            <div class="summary-value" style="color: #991b1b;">{{ $stats['unpaid_customers'] }}</div>
            <div class="summary-detail">₱{{ number_format($stats['total_outstanding'], 2) }} outstanding</div>
        </div>
    </div>
    
    <div class="summary-card summary-partial">
        <div class="summary-icon" style="background: #fef3c7; color: #b45309;">
            <i data-lucide="clock" size="24"></i>
        </div>
        <div class="summary-content">
            <div class="summary-label">Partial Payments</div>
            <div class="summary-value" style="color: #b45309;">{{ $stats['partial_customers'] }}</div>
            <div class="summary-detail">Awaiting completion</div>
        </div>
    </div>
    
    <div class="summary-card summary-paid">
        <div class="summary-icon" style="background: #ecfdf5; color: #059669;">
            <i data-lucide="check-circle" size="24"></i>
        </div>
        <div class="summary-content">
            <div class="summary-label">Settled Customers</div>
            <div class="summary-value" style="color: #059669;">{{ $stats['paid_customers'] }}</div>
            <div class="summary-detail">All payments clear</div>
        </div>
    </div>
</div>

<div class="card table-card">
    <!-- Filter Section -->
    <div class="filter-section">
        <form class="table-top" method="GET" action="{{ route('aquaheart.customers.index') }}" id="customerSearchForm">
            <div class="search-box">
                <i data-lucide="search" size="16"></i>
                <input type="text" name="search" id="customerSearchInput" value="{{ request('search') }}" placeholder="Search by name, uid or phone number..." autocomplete="off">
            </div>
            
            <!-- Status Filter Buttons -->
            <div class="status-filter-buttons">
                <a href="{{ route('aquaheart.customers.index', ['search' => request('search'), 'status' => 'all']) }}" 
                   class="filter-btn {{ $statusFilter === 'all' ? 'active' : '' }}">
                    All ({{ $stats['total_customers'] }})
                </a>
                <a href="{{ route('aquaheart.customers.index', ['search' => request('search'), 'status' => 'unpaid']) }}" 
                   class="filter-btn unpaid {{ $statusFilter === 'unpaid' ? 'active' : '' }}">
                    Unpaid ({{ $stats['unpaid_customers'] }})
                </a>
                <a href="{{ route('aquaheart.customers.index', ['search' => request('search'), 'status' => 'partial']) }}" 
                   class="filter-btn partial {{ $statusFilter === 'partial' ? 'active' : '' }}">
                    Partial ({{ $stats['partial_customers'] }})
                </a>
                <a href="{{ route('aquaheart.customers.index', ['search' => request('search'), 'status' => 'paid']) }}" 
                   class="filter-btn paid {{ $statusFilter === 'paid' ? 'active' : '' }}">
                    Paid ({{ $stats['paid_customers'] }})
                </a>
            </div>
            
            <div class="filter-box" style="display: flex; gap: 8px; margin-left: auto;">
                @if(request()->filled('search') || request()->filled('status'))
                    <a href="{{ route('aquaheart.customers.index') }}" class="btn-outline" style="text-decoration: none; display: inline-flex; align-items: center;">
                        Clear
                    </a>
                @endif
                <button type="submit" class="btn-outline">
                    <i data-lucide="sliders-horizontal" size="16"></i>
                    Filter
                </button>
            </div>
        </form>
    </div>

    @if ($customers->count())
        <div class="scroll-table">
            <table class="modern-table">
                <thead>
                    <tr>
                        <th>User Identity</th>
                        <th>Contact Number</th>
                        <th>Total Spent</th>
                        <th>Outstanding Balance</th>
                        <th>Payment Status</th>
                        <th>Loyalty Points</th>
                        <th style="text-align: right;">Options</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($customers as $customer)
                        @php
                            $totalSpent = ($customer->total_paid ?? 0) + ($customer->total_unpaid ?? 0) + ($customer->total_partial ?? 0);
                            $outstanding = ($customer->total_unpaid ?? 0) + ($customer->total_partial ?? 0);
                            if ($outstanding > 0) {
                                $statusClass = $customer->total_unpaid > 0 ? 'status-unpaid' : 'status-partial';
                                $statusLabel = $customer->total_unpaid > 0 ? 'Unpaid' : 'Partial';
                                $rowClass = $customer->total_unpaid > 0 ? 'row-unpaid' : 'row-partial';
                            } else {
                                $statusClass = 'status-paid';
                                $statusLabel = 'Paid';
                                $rowClass = 'row-paid';
                            }
                        @endphp
                        <tr class="customer-row {{ $rowClass }}">
                            <td>
                                <div class="user-row">
                                    <div class="user-avatar">{{ substr($customer->name, 0, 1) }}</div>
                                    <div class="user-info">
                                        <div class="user-name">{{ $customer->name }}</div>
                                        <div class="user-uid">UID-{{ strtoupper(substr($customer->id, 0, 8)) }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="cell-text">{{ $customer->phone ?? 'Private' }}</td>
                            <td class="cell-text"><strong>₱{{ number_format($totalSpent, 2) }}</strong></td>
                            <td class="cell-balance">
                                @if($outstanding > 0)
                                    <div class="balance-highlight">₱{{ number_format($outstanding, 2) }}</div>
                                @else
                                    <span style="color: #059669;">₱0.00</span>
                                @endif
                            </td>
                            <td>
                                <span class="payment-badge {{ $statusClass }}">{{ $statusLabel }}</span>
                            </td>
                            <td class="cell-text">{{ number_format($customer->loyalty_points) }}</td>
                            <td style="text-align: right;">
                                <div class="action-row">
                                    <a href="{{ route('aquaheart.customers.show', $customer) }}" class="icon-link b-blue" title="View"><i data-lucide="arrow-right"></i></a>
                                    <a href="{{ route('aquaheart.customers.edit', $customer) }}" class="icon-link b-gray" title="Edit"><i data-lucide="edit-2"></i></a>
                                    <form method="POST" action="{{ route('aquaheart.customers.destroy', $customer) }}" style="display: inline;" data-ajax-delete data-delete-label="customer {{ $customer->name }}">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="icon-link b-red" title="Delete">
                                            <i data-lucide="trash-2"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="table-footer">
            {{ $customers->links() }}
        </div>
    @else
        <div class="blank-slate">
            <div class="slate-icon"><i data-lucide="users"></i></div>
            <h4>Directory Empty</h4>
            <p>Ready to start? Add your first household record here.</p>
        </div>
    @endif
</div>

@push('styles')
<style>
    /* Summary Cards Grid */
    .summary-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
        gap: 20px;
        margin-bottom: 24px;
    }

    .summary-card {
        display: flex;
        align-items: center;
        gap: 16px;
        padding: 20px;
        background: white;
        border: 1px solid var(--border);
        border-radius: 12px;
        transition: var(--transition);
    }

    .summary-card:hover {
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
        transform: translateY(-2px);
    }

    .summary-icon {
        width: 56px;
        height: 56px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }

    .summary-content {
        flex: 1;
    }

    .summary-label {
        font-size: 0.8rem;
        color: var(--text-muted);
        text-transform: uppercase;
        font-weight: 700;
        letter-spacing: 0.5px;
        margin-bottom: 6px;
    }

    .summary-value {
        font-size: 1.5rem;
        font-weight: 800;
        margin-bottom: 4px;
    }

    .summary-detail {
        font-size: 0.75rem;
        color: var(--text-muted);
        font-weight: 600;
    }

    /* Filter Section */
    .filter-section {
        padding: 20px 24px;
        border-bottom: 1px solid var(--border);
    }

    .table-top {
        display: flex;
        align-items: center;
        gap: 16px;
        flex-wrap: wrap;
    }

    .status-filter-buttons {
        display: flex;
        gap: 8px;
        flex-wrap: wrap;
        align-items: center;
    }

    .filter-btn {
        padding: 8px 14px;
        background: white;
        border: 1px solid var(--border);
        border-radius: 8px;
        font-size: 0.8rem;
        font-weight: 700;
        text-decoration: none;
        cursor: pointer;
        transition: var(--transition);
        color: var(--text-main);
        display: inline-flex;
        align-items: center;
        white-space: nowrap;
    }

    .filter-btn:hover {
        background: var(--bg);
        border-color: #bfdbfe;
    }

    .filter-btn.active {
        background: #3b82f6;
        border-color: #3b82f6;
        color: white;
        box-shadow: 0 4px 12px rgba(59, 130, 246, 0.3);
    }

    .filter-btn.unpaid:hover {
        background: #fecaca;
        border-color: #fca5a5;
    }

    .filter-btn.unpaid.active {
        background: #991b1b;
        border-color: #991b1b;
        color: white;
    }

    .filter-btn.partial:hover {
        background: #fcd34d;
        border-color: #fbca04;
    }

    .filter-btn.partial.active {
        background: #b45309;
        border-color: #b45309;
        color: white;
    }

    .filter-btn.paid:hover {
        background: #d1fae5;
        border-color: #a7f3d0;
    }

    .filter-btn.paid.active {
        background: #059669;
        border-color: #059669;
        color: white;
    }

    /* Alert Success */
    .alert-success-minimal { display: flex; align-items: center; gap: 10px; background: #f0fdf4; border: 1px solid #bbf7d0; color: #166534; padding: 12px 20px; border-radius: 10px; font-weight: 700; font-size: 0.85rem; margin-bottom: 24px; }
    
    .table-card { padding: 0; }
    
    .payment-badge {
        display: inline-block;
        padding: 6px 12px;
        border-radius: 8px;
        font-size: 0.75rem;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    
    .payment-badge.status-paid {
        background: #ecfdf5;
        color: #059669;
    }
    
    .payment-badge.status-unpaid {
        background: #fee2e2;
        color: #991b1b;
    }
    
    .payment-badge.status-partial {
        background: #fef3c7;
        color: #b45309;
    }
    
    .search-box { position: relative; flex: 1; max-width: 450px; }
    .search-box i { position: absolute; left: 16px; top: 50%; transform: translateY(-50%); color: var(--text-muted); }
    .search-box input { width: 100%; border: 1px solid var(--border); background: var(--bg); padding: 10px 16px 10px 48px; border-radius: 10px; font-size: 0.85rem; transition: var(--transition); }
    .search-box input:focus { border-color: var(--accent); background: white; outline: none; }

    .btn-outline { background: white; border: 1px solid var(--border); border-radius: 10px; padding: 10px 16px; color: var(--primary); font-size: 0.85rem; font-weight: 700; cursor: pointer; display: flex; align-items: center; gap: 8px; transition: var(--transition); }
    .btn-outline:hover { background: var(--bg); }

    .modern-table { width: 100%; border-collapse: collapse; }
    .modern-table th { text-align: left; padding: 16px 24px; border-bottom: 1px solid var(--border); font-size: 0.75rem; font-weight: 800; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.5px; }
    .modern-table td { padding: 16px 24px; border-bottom: 1px solid var(--border); }
    .modern-table tbody tr:hover { background: #fbfcfe; }

    /* Row highlighting based on payment status */
    .customer-row.row-unpaid {
        background: #fff5f5;
        border-left: 3px solid #991b1b;
    }

    .customer-row.row-unpaid:hover {
        background: #fef2f2;
    }

    .customer-row.row-partial {
        background: #fffbeb;
        border-left: 3px solid #b45309;
    }

    .customer-row.row-partial:hover {
        background: #fef9e7;
    }

    .customer-row.row-paid {
        border-left: 3px solid #059669;
    }

    .customer-row.row-paid:hover {
        background: #fbfcfe;
    }

    /* Balance highlighting */
    .cell-balance {
        font-weight: 600;
    }

    .balance-highlight {
        color: #ef4444;
        font-weight: 800;
        padding: 6px 12px;
        background: #fef2f2;
        border-radius: 6px;
        display: inline-block;
    }

    .user-row { display: flex; align-items: center; gap: 12px; }
    .user-avatar { width: 34px; height: 34px; background: var(--accent-soft); border-radius: 8px; color: var(--accent); display: flex; align-items: center; justify-content: center; font-weight: 800; font-size: 0.8rem; }
    .user-name { display: block; font-size: 0.9rem; font-weight: 700; color: var(--primary); }
    .user-uid { font-size: 0.7rem; color: var(--text-muted); font-weight: 600; }

    .cell-text { font-size: 0.85rem; color: var(--text-main); font-weight: 600; }
    .cell-text.secondary { font-size: 0.75rem; color: var(--text-muted); font-weight: 500; }

    .action-row { display: flex; gap: 8px; justify-content: flex-end; }
    .icon-link { width: 32px; height: 32px; border-radius: 8px; display: flex; align-items: center; justify-content: center; text-decoration: none; border: none; cursor: pointer; transition: var(--transition); }
    .icon-link i { width: 16px; height: 16px; }
    
    .icon-link.b-blue { color: #3b82f6; background: #eff6ff; }
    .icon-link.b-gray { color: #64748b; background: #f1f5f9; }
    .icon-link.b-red { color: #ef4444; background: #fef2f2; }

    .icon-link:hover { transform: translateY(-2px); opacity: 0.8; }

    .table-footer { padding: 24px; }
    .blank-slate { padding: 80px 40px; text-align: center; }
    .slate-icon { color: var(--border); margin-bottom: 16px; }
    .blank-slate h4 { font-family: 'Plus Jakarta Sans', sans-serif; font-size: 1.1rem; color: var(--primary); margin-bottom: 8px; }
    .blank-slate p { font-size: 0.85rem; color: var(--text-muted); }

    /* Responsive */
    @media (max-width: 768px) {
        .summary-grid {
            grid-template-columns: 1fr;
        }

        .table-top {
            flex-direction: column;
            align-items: stretch;
        }

        .search-box {
            max-width: 100%;
        }

        .status-filter-buttons {
            width: 100%;
        }

        .modern-table th,
        .modern-table td {
            padding: 12px 16px;
            font-size: 0.8rem;
        }
    }
</style>
@endpush

@push('scripts')
<script>
    (function () {
        const form = document.getElementById('customerSearchForm');
        const input = document.getElementById('customerSearchInput');

        if (!form || !input) {
            return;
        }

        let timeoutId = null;

        input.addEventListener('input', function () {
            window.clearTimeout(timeoutId);
            timeoutId = window.setTimeout(function () {
                form.submit();
            }, 300);
        });
    })();
</script>
@endpush
@endsection
