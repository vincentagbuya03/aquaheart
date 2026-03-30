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

@if (session('success'))
    <div class="alert-success-minimal">
        <i data-lucide="check" size="16"></i>
        <span>{{ session('success') }}</span>
    </div>
@endif

<div class="card table-card">
    <div class="table-top">
        <div class="search-box">
            <i data-lucide="search" size="16"></i>
            <input type="text" placeholder="Search by name, uid or phone number...">
        </div>
        <div class="filter-box">
            <button class="btn-outline">
                <i data-lucide="sliders-horizontal" size="16"></i>
                Filter
            </button>
        </div>
    </div>

    @if ($customers->count())
        <div class="scroll-table">
            <table class="modern-table">
                <thead>
                    <tr>
                        <th>User Identity</th>
                        <th>Contact Number</th>
                        <th>Loyalty Points</th>
                        <th>Registered On</th>
                        <th style="min-width: 300px;">Service Address</th>
                        <th style="text-align: right;">Options</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($customers as $customer)
                        <tr>
                            <td>
                                <div class="user-row">
                                    <div class="user-avatar">{{ substr($customer->name, 0, 1) }}</div>
                                    <div class="user-info">
                                        <div class="user-name">{{ $customer->name }}</div>
                                        <div class="user-uid">UID-{{ $customer->id + 1000 }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="cell-text">{{ $customer->phone ?? 'Private' }}</td>
                            <td class="cell-text">{{ number_format($customer->loyalty_points) }}</td>
                            <td class="cell-text secondary">{{ $customer->created_at->format('M d, Y') }}</td>
                            <td class="cell-text">{{ $customer->address ?? 'Manual Refill Only' }}</td>
                            <td style="text-align: right;">
                                <div class="action-row">
                                    <a href="{{ route('aquaheart.customers.show', $customer) }}" class="icon-link b-blue" title="View"><i data-lucide="arrow-right"></i></a>
                                    <a href="{{ route('aquaheart.customers.edit', $customer) }}" class="icon-link b-gray" title="Edit"><i data-lucide="edit-2"></i></a>
                                    <form method="POST" action="{{ route('aquaheart.customers.destroy', $customer) }}" style="display: inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="icon-link b-red" title="Delete" onclick="return confirm('Confirm removal?')">
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
    .alert-success-minimal { display: flex; align-items: center; gap: 10px; background: #f0fdf4; border: 1px solid #bbf7d0; color: #166534; padding: 12px 20px; border-radius: 10px; font-weight: 700; font-size: 0.85rem; margin-bottom: 24px; }
    
    .table-card { padding: 0; }
    .table-top { padding: 24px; border-bottom: 1px solid var(--border); display: flex; justify-content: space-between; gap: 20px; }
    
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
</style>
@endpush
@endsection
