@extends('layouts.aquaheart')

@section('title', 'Customer Statistics')
@section('page_title', 'Customer Analytics')
@section('page_subtitle', 'Review spending patterns, refill frequency, and customer lifetime value.')

@section('content')
<div class="summary-card card">
    <p class="summary-label">Total Customers</p>
    <p class="summary-value">{{ $totalCustomers }}</p>
</div>

<div class="card section">
    <div class="section-head">
        <h2>Customer Performance</h2>
        <p>Sorted by highest total spending.</p>
    </div>

    @if($customerStats->count())
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>Customer Name</th>
                        <th>Total Refills</th>
                        <th>Total Spent</th>
                        <th>Avg per Refill</th>
                        <th>First Refill</th>
                        <th>Last Refill</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($customerStats as $stat)
                        <tr>
                            <td><strong>{{ $stat->name }}</strong></td>
                            <td>{{ $stat->refill_count ?? 0 }}</td>
                            <td>PHP {{ number_format($stat->total_spent ?? 0, 2) }}</td>
                            <td>PHP {{ number_format($stat->avg_spent ?? 0, 2) }}</td>
                            <td>{{ $stat->first_refill ? \Carbon\Carbon::parse($stat->first_refill)->format('M d, Y') : '-' }}</td>
                            <td>{{ $stat->last_refill ? \Carbon\Carbon::parse($stat->last_refill)->format('M d, Y') : '-' }}</td>
                            <td><a href="{{ route('aquaheart.customers.show', $stat->id) }}" class="view-link">View</a></td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="pagination-wrapper">
            {{ $customerStats->links() }}
        </div>
    @else
        <p class="empty-state">No customer analytics available yet.</p>
    @endif
</div>

@push('styles')
<style>
    .summary-card { margin-bottom: 24px; background: linear-gradient(135deg, #0f172a 0%, #1d4ed8 100%); color: white; }
    .summary-label { font-size: 0.75rem; text-transform: uppercase; letter-spacing: 1px; opacity: 0.85; }
    .summary-value { font-size: 3rem; font-weight: 800; margin-top: 8px; }
    .section-head { margin-bottom: 18px; }
    .section-head h2 { color: var(--primary); font-size: 1.1rem; font-weight: 800; }
    .section-head p { color: var(--text-muted); font-size: 0.85rem; margin-top: 4px; }
    .table-responsive { overflow-x: auto; }
    .table { width: 100%; border-collapse: collapse; }
    .table th, .table td { padding: 14px 12px; border-bottom: 1px solid var(--border); text-align: left; }
    .table th { font-size: 0.75rem; color: var(--text-muted); text-transform: uppercase; }
    .view-link { color: var(--accent); font-weight: 700; text-decoration: none; }
    .pagination-wrapper { margin-top: 20px; }
    .empty-state { color: var(--text-muted); }
</style>
@endpush
@endsection
