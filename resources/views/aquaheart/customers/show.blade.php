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
<div class="detail-grid">
    <div class="card detail-card">
        <div class="detail-row"><span class="label">Phone</span><strong>{{ $customer->phone ?: 'Not provided' }}</strong></div>
        <div class="detail-row"><span class="label">Address</span><strong>{{ $customer->address ?: 'Walk-in only' }}</strong></div>
        <div class="detail-row"><span class="label">Member Since</span><strong>{{ $customer->created_at->format('M d, Y') }}</strong></div>
        <div class="detail-row"><span class="label">Loyalty Points</span><strong>{{ number_format($customer->loyalty_points) }}</strong></div>
    </div>

    <div class="card detail-card">
        <div class="detail-row"><span class="label">Total Refills</span><strong>{{ $customer->refills->count() }}</strong></div>
        <div class="detail-row"><span class="label">Total Spent</span><strong>PHP {{ number_format($customer->refills->sum('amount'), 2) }}</strong></div>
        <div class="detail-row"><span class="label">Average Ticket</span><strong>PHP {{ number_format($customer->refills->count() > 0 ? $customer->refills->avg('amount') : 0, 2) }}</strong></div>
        <div class="detail-row"><span class="label">Last Refill</span><strong>{{ optional($customer->refills->sortByDesc('refill_date')->first()?->refill_date)->format('M d, Y') ?: 'No activity yet' }}</strong></div>
    </div>
</div>

<div class="card history-card">
    <div class="history-head">
        <h3>Transaction History</h3>
        <p>All recorded sales and refills linked to this customer.</p>
    </div>

    @if ($customer->refills->count())
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
                @foreach ($customer->refills->sortByDesc('refill_date') as $refill)
                    <tr>
                        <td>{{ $refill->receipt_number ?: 'Pending Number' }}</td>
                        <td>{{ $refill->product->name ?? 'Unknown Product' }}</td>
                        <td>{{ $refill->quantity }}</td>
                        <td>{{ ucfirst($refill->payment_status) }}</td>
                        <td>PHP {{ number_format($refill->amount, 2) }}</td>
                        <td>{{ $refill->refill_date->format('M d, Y') }}</td>
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
    @media (max-width: 900px) { .detail-grid { grid-template-columns: 1fr; } }
</style>
@endpush
@endsection
