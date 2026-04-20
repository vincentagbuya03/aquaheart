@extends('layouts.aquaheart')

@section('title', 'Transaction Details')
@section('page_title', $refill->receipt_number ?: 'Transaction Details')
@section('page_subtitle', 'Review customer, product, payment, and service information for this record.')

@section('page_actions')
<a href="{{ route('aquaheart.refills.edit', $refill) }}" class="btn-primary">
    <i data-lucide="edit-2" size="18"></i>
    Edit Transaction
</a>
@endsection

@section('content')
<div class="detail-grid">
    <div class="card detail-card">
        <div class="detail-row"><span class="label">Customer</span><strong>{{ $refill->customer->name }}</strong></div>
        <div class="detail-row"><span class="label">Product</span><strong>{{ $refill->product->name ?? 'Unknown Product' }}</strong></div>
        <div class="detail-row"><span class="label">Quantity</span><strong>{{ $refill->quantity }}</strong></div>
        <div class="detail-row"><span class="label">Unit Price</span><strong>PHP {{ number_format($refill->unit_price, 2) }}</strong></div>
        <div class="detail-row"><span class="label">Total Amount</span><strong>PHP {{ number_format(($refill->quantity ?? 0) * ($refill->unit_price ?? 0), 2) }}</strong></div>
    </div>
    <div class="card detail-card">
        <div class="detail-row"><span class="label">Payment Status</span><strong>{{ ucfirst($refill->payment_status ?? 'paid') }}</strong></div>
        <div class="detail-row"><span class="label">Service Type</span><strong>{{ ucfirst(str_replace('_', ' ', $refill->service_type ?? 'walk_in')) }}</strong></div>
        <div class="detail-row"><span class="label">Refill Date</span><strong>{{ optional($refill->refill_date)->format('M d, Y') ?? optional($refill->created_at)->format('M d, Y') ?? 'N/A' }}</strong></div>
        <div class="detail-row"><span class="label">Recorded At</span><strong>{{ optional($refill->created_at)->format('M d, Y h:i A') ?? 'N/A' }}</strong></div>
        <div class="detail-row notes"><span class="label">Notes</span><p>{{ $refill->notes ?: 'No notes added.' }}</p></div>
    </div>
</div>

@push('styles')
<style>
    .detail-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 24px; }
    .detail-card { display: flex; flex-direction: column; gap: 18px; }
    .detail-row { display: flex; justify-content: space-between; gap: 16px; padding-bottom: 16px; border-bottom: 1px solid var(--border); }
    .detail-row.notes { display: block; }
    .label { color: var(--text-muted); font-size: 0.8rem; font-weight: 800; text-transform: uppercase; }
    .detail-row p { margin-top: 10px; color: var(--text-main); line-height: 1.6; }
    @media (max-width: 900px) { .detail-grid { grid-template-columns: 1fr; } }
</style>
@endpush
@endsection
