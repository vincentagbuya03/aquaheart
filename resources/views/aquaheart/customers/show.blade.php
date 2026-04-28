@extends('layouts.aquaheart')

@section('title', 'Customer Details')
@section('page_title', $customer->name)
@section('page_subtitle', 'View customer profile, loyalty standing, and refill history.')

@section('page_actions')
<a href="{{ route('aquaheart.customers.edit', $customer) }}" class="btn-primary">
    <i data-lucide="edit-2" size="18"></i>
    Edit Customer
</a>
@endsection

@section('content')
@php
    $sortedRefills = $customer->refills
        ->sortByDesc(fn ($refill) => $refill->refill_date ?? $refill->created_at)
        ->values();

    $totalRefills = $sortedRefills->count();

    $totalSpent = $sortedRefills->sum(function ($refill) {
        $computedAmount = (float) ($refill->quantity ?? 0) * (float) ($refill->unit_price ?? 0);

        // Keep backwards compatibility for older records where amount is the only populated value.
        return $computedAmount > 0 ? $computedAmount : (float) ($refill->amount ?? 0);
    });

    $averageTicket = $totalRefills > 0 ? $totalSpent / $totalRefills : 0;
    $lastRefill = $sortedRefills->first();
    
    // Calculate payment status breakdown
    $unpaidAmount = $sortedRefills
        ->filter(fn ($refill) => ($refill->paymentStatus?->name ?? strtolower($refill->payment_status ?? '')) === 'unpaid')
        ->sum(fn ($refill) => ((float) ($refill->quantity ?? 0) * (float) ($refill->unit_price ?? 0)) ?: (float) ($refill->amount ?? 0));
    
    // Sum of ONLY the outstanding balances for partial payments
    $partialBalanceAmount = $sortedRefills
        ->filter(fn ($refill) => ($refill->paymentStatus?->name ?? strtolower($refill->payment_status ?? '')) === 'partial')
        ->sum(fn ($refill) => (float) ($refill->partial_amount ?? 0));
        
    // Sum of ONLY the amounts ALREADY PAID for partial payments
    $partialPaidAmount = $sortedRefills
        ->filter(fn ($refill) => ($refill->paymentStatus?->name ?? strtolower($refill->payment_status ?? '')) === 'partial')
        ->sum(fn ($refill) => (float) ($refill->paid_amount ?? 0));
    
    // Correctly calculate total paid: (Fully Paid Records) + (Paid portion of Partial Records)
    $fullyPaidAmount = $sortedRefills
        ->filter(fn ($refill) => ($refill->paymentStatus?->name ?? strtolower($refill->payment_status ?? '')) === 'paid')
        ->sum(fn ($refill) => ((float) ($refill->quantity ?? 0) * (float) ($refill->unit_price ?? 0)) ?: (float) ($refill->amount ?? 0));
        
    $paidAmount = $fullyPaidAmount + $partialPaidAmount;
    $outstandingAmount = $unpaidAmount + $partialBalanceAmount;
@endphp

<div class="detail-grid">
    <div class="card detail-card">
        <div class="detail-row"><span class="label">Phone</span><strong>{{ $customer->phone ?: 'Not provided' }}</strong></div>
        <div class="detail-row"><span class="label">Address</span><strong>{{ $customer->address ?: 'Walk-in only' }}</strong></div>
        <div class="detail-row"><span class="label">Member Since</span><strong>{{ optional($customer->created_at)->format('M d, Y') ?? 'N/A' }}</strong></div>
        <div class="detail-row"><span class="label">Loyalty Points</span><strong>{{ number_format($customer->loyalty_points) }}</strong></div>
    </div>

    <div class="card detail-card">
        <div class="detail-row"><span class="label">Total Refills</span><strong>{{ $totalRefills }}</strong></div>
        <div class="detail-row"><span class="label">Total Spent</span><strong>PHP {{ number_format($totalSpent, 2) }}</strong></div>
        <div class="detail-row"><span class="label">Average Ticket</span><strong>PHP {{ number_format($averageTicket, 2) }}</strong></div>
        <div class="detail-row"><span class="label">Last Refill</span><strong>{{ optional($lastRefill?->refill_date ?? $lastRefill?->created_at)->format('M d, Y') ?: 'No activity yet' }}</strong></div>
    </div>
    
    <div class="card detail-card">
        <div class="detail-row">
            <span class="label">Paid Amount</span>
            <strong style="color: #059669;">₱{{ number_format($paidAmount, 2) }}</strong>
        </div>
        <div class="detail-row">
            <span class="label">Partial Balances</span>
            <strong style="color: #b45309;">₱{{ number_format($partialBalanceAmount, 2) }}</strong>
        </div>
        <div class="detail-row">
            <span class="label">Full Arrears</span>
            <strong style="color: #991b1b;">₱{{ number_format($unpaidAmount, 2) }}</strong>
        </div>
        <div class="detail-row">
            <span class="label">Total Outstanding</span>
            <strong style="color: {{ $outstandingAmount > 0 ? '#ef4444' : '#059669' }};">₱{{ number_format($outstandingAmount, 2) }}</strong>
        </div>
    </div>
</div>

<div class="card history-card">
    <div class="history-head">
        <h3>Transaction History</h3>
        <p>All recorded sales and refills linked to this customer.</p>
    </div>

    @if ($sortedRefills->count())
        <table class="modern-table">
            <thead>
                <tr>
                    <th>Receipt</th>
                    <th>Product</th>
                    <th>Quantity</th>
                    <th>Payment</th>
                    <th>Total</th>
                    <th>Date</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($sortedRefills as $refill)
                    @php
                        $lineTotal = ((float) ($refill->quantity ?? 0) * (float) ($refill->unit_price ?? 0));
                        if ($lineTotal <= 0) {
                            $lineTotal = (float) ($refill->amount ?? 0);
                        }
                    @endphp
                    <tr>
                        <td>{{ $refill->receipt_number ?: 'Pending Number' }}</td>
                        <td>{{ $refill->product->name ?? 'Unknown Product' }}</td>
                        <td>{{ $refill->quantity }}</td>
                        <td>
                            @php
                                $status = $refill->paymentStatus?->name ?? strtolower($refill->payment_status ?? 'pending');
                                $statusClass = match($status) {
                                    'paid' => 'status-paid',
                                    'unpaid' => 'status-unpaid',
                                    'partial' => 'status-partial',
                                    default => 'status-pending'
                                };
                                $statusLabel = ucfirst($status);
                            @endphp
                            <span class="payment-badge {{ $statusClass }}">{{ $statusLabel }}</span>
                        </td>
                        <td>PHP {{ number_format($lineTotal, 2) }}</td>
                        <td>{{ optional($refill->refill_date)->format('M d, Y') ?? optional($refill->created_at)->format('M d, Y') ?? 'N/A' }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <p class="empty-state">No refill history yet for this customer.</p>
    @endif
</div>

@push('styles')
<style>
    .detail-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 24px; margin-bottom: 24px; }
    .detail-card { display: flex; flex-direction: column; gap: 18px; }
    .detail-row { display: flex; justify-content: space-between; gap: 16px; padding-bottom: 16px; border-bottom: 1px solid var(--border); }
    .label { color: var(--text-muted); font-size: 0.8rem; font-weight: 800; text-transform: uppercase; }
    .history-head { margin-bottom: 20px; }
    .history-head h3 { font-size: 1.1rem; font-weight: 800; color: var(--primary); }
    .history-head p { color: var(--text-muted); font-size: 0.85rem; margin-top: 4px; }
    .modern-table { width: 100%; border-collapse: collapse; }
    .modern-table th, .modern-table td { padding: 16px 12px; border-bottom: 1px solid var(--border); text-align: left; }
    .modern-table th { font-size: 0.75rem; font-weight: 800; color: var(--text-muted); text-transform: uppercase; }
    .empty-state { color: var(--text-muted); }
    
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
    
    .payment-badge.status-pending {
        background: #f3f4f6;
        color: #374151;
    }
    
    @media (max-width: 900px) { .detail-grid { grid-template-columns: 1fr; } }
</style>
@endpush
@endsection
