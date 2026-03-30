@extends('layouts.aquaheart')

@section('title', 'Refill Logs')
@section('page_title', 'Sales and Refill Transactions')
@section('page_subtitle', 'Track receipts, payment status, service type, and transaction totals.')

@section('page_actions')
<div style="display: flex; gap: 12px;">
    <a href="{{ route('aquaheart.refills.create') }}" class="btn-primary">
        <i data-lucide="plus" size="18"></i>
        Log Record
    </a>
    <a href="{{ route('aquaheart.reports.export-refills') }}" class="btn-primary" style="background: white; color: var(--primary); border: 1px solid var(--border);">
        <i data-lucide="download" size="18"></i>
        Export CSV
    </a>
</div>
@endsection

@section('content')
@if (session('success'))
    <div class="alert-success-minimal">
        <i data-lucide="check" size="16"></i>
        <span>{{ session('success') }}</span>
    </div>
@endif

<div class="card table-card">
    @if ($refills->count())
        <div class="scroll-table">
            <table class="modern-table">
                <thead>
                    <tr>
                        <th>Receipt</th>
                        <th>Customer</th>
                        <th>Product</th>
                        <th>Service</th>
                        <th>Payment</th>
                        <th>Total</th>
                        <th>Date</th>
                        <th style="text-align: right;">Options</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($refills as $refill)
                        <tr>
                            <td>
                                <div class="receipt-number">{{ $refill->receipt_number ?: 'Pending Number' }}</div>
                                <div class="receipt-sub">{{ $refill->quantity }} unit(s) x PHP {{ number_format($refill->unit_price, 2) }}</div>
                            </td>
                            <td class="cell-text">{{ $refill->customer->name ?? 'Deleted Customer' }}</td>
                            <td class="cell-text">{{ $refill->product->name ?? 'Unknown Product' }}</td>
                            <td><span class="badge service">{{ ucfirst(str_replace('_', ' ', $refill->service_type)) }}</span></td>
                            <td><span class="badge {{ $refill->payment_status }}">{{ ucfirst($refill->payment_status) }}</span></td>
                            <td class="cell-text">PHP {{ number_format($refill->amount, 2) }}</td>
                            <td class="cell-text secondary">{{ $refill->refill_date->format('M d, Y') }}</td>
                            <td style="text-align: right;">
                                <div class="action-row">
                                    <a href="{{ route('aquaheart.refills.show', $refill) }}" class="icon-link b-blue"><i data-lucide="info"></i></a>
                                    <a href="{{ route('aquaheart.refills.edit', $refill) }}" class="icon-link b-gray"><i data-lucide="edit-2"></i></a>
                                    <form method="POST" action="{{ route('aquaheart.refills.destroy', $refill) }}" style="display: inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="icon-link b-red" onclick="return confirm('Remove this record?')">
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
            {{ $refills->links() }}
        </div>
    @else
        <div class="blank-slate">
            <div class="slate-icon"><i data-lucide="clipboard-list"></i></div>
            <h4>No Transactions Yet</h4>
            <p>Start recording refill and sales activity to build your operational history.</p>
        </div>
    @endif
</div>

@push('styles')
<style>
    .alert-success-minimal { display: flex; align-items: center; gap: 10px; background: #f0fdf4; border: 1px solid #bbf7d0; color: #166534; padding: 12px 20px; border-radius: 10px; font-weight: 700; font-size: 0.85rem; margin-bottom: 24px; }
    .table-card { padding: 0; }
    .modern-table { width: 100%; border-collapse: collapse; }
    .modern-table th, .modern-table td { padding: 16px 24px; border-bottom: 1px solid var(--border); text-align: left; }
    .modern-table th { font-size: 0.75rem; font-weight: 800; color: var(--text-muted); text-transform: uppercase; }
    .receipt-number { font-weight: 800; color: var(--primary); }
    .receipt-sub { margin-top: 4px; font-size: 0.75rem; color: var(--text-muted); }
    .cell-text { font-size: 0.85rem; color: var(--text-main); font-weight: 700; }
    .cell-text.secondary { font-size: 0.75rem; color: var(--text-muted); }
    .badge { display: inline-flex; padding: 6px 10px; border-radius: 999px; font-size: 0.75rem; font-weight: 800; }
    .badge.service { background: #e0f2fe; color: #0369a1; }
    .badge.paid { background: #dcfce7; color: #166534; }
    .badge.unpaid { background: #fee2e2; color: #b91c1c; }
    .badge.partial { background: #fef3c7; color: #b45309; }
    .action-row { display: flex; gap: 8px; justify-content: flex-end; }
    .icon-link { width: 32px; height: 32px; border-radius: 8px; display: inline-flex; align-items: center; justify-content: center; text-decoration: none; border: none; cursor: pointer; }
    .icon-link.b-blue { color: #3b82f6; background: #eff6ff; }
    .icon-link.b-gray { color: #64748b; background: #f1f5f9; }
    .icon-link.b-red { color: #ef4444; background: #fef2f2; }
    .table-footer { padding: 24px; }
    .blank-slate { padding: 72px 32px; text-align: center; color: var(--text-muted); }
</style>
@endpush
@endsection
