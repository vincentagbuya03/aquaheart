@extends('layouts.aquaheart')

@section('title', 'Products')
@section('page_title', 'Product and Inventory Management')
@section('page_subtitle', 'Maintain bottle pricing, stock availability, and reorder levels.')

@section('page_actions')
<a href="{{ route('aquaheart.products.create') }}" class="btn-primary">
    <i data-lucide="plus" size="18"></i>
    Add Product
</a>
@endsection

@section('content')
@if (session('success'))
    <div class="alert-success-minimal">
        <i data-lucide="check" size="16"></i>
        <span>{{ session('success') }}</span>
    </div>
@endif

<div class="summary-grid">
    <div class="card summary-card">
        <span class="summary-label">Active Products</span>
        <strong class="summary-value">{{ $products->where('is_active', true)->count() }}</strong>
    </div>
    <div class="card summary-card">
        <span class="summary-label">Units on Hand</span>
        <strong class="summary-value">{{ $products->sum('stock_quantity') }}</strong>
    </div>
    <div class="card summary-card">
        <span class="summary-label">Low Stock Items</span>
        <strong class="summary-value">{{ $products->filter(fn ($product) => $product->stock_quantity <= $product->reorder_level)->count() }}</strong>
    </div>
</div>

<div class="card table-card">
    @if ($products->count())
        <div class="scroll-table">
            <table class="modern-table">
                <thead>
                    <tr>
                        <th>Product</th>
                        <th>Unit Price</th>
                        <th>Stock</th>
                        <th>Reorder Level</th>
                        <th>Status</th>
                        <th style="text-align: right;">Options</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($products as $product)
                        <tr>
                            <td>
                                <div class="product-name">{{ $product->name }}</div>
                                <div class="product-desc">{{ $product->description ?: 'No description provided.' }}</div>
                            </td>
                            <td>PHP {{ number_format($product->price, 2) }}</td>
                            <td>
                                <span class="stock-pill {{ $product->stock_quantity <= $product->reorder_level ? 'low' : '' }}">
                                    {{ $product->stock_quantity }}
                                </span>
                            </td>
                            <td>{{ $product->reorder_level }}</td>
                            <td>
                                <span class="status-badge {{ $product->is_active ? 'active' : 'inactive' }}">
                                    {{ $product->is_active ? 'Active' : 'Inactive' }}
                                </span>
                            </td>
                            <td style="text-align: right;">
                                <div class="action-row">
                                    <a href="{{ route('aquaheart.products.show', $product) }}" class="icon-link b-blue"><i data-lucide="arrow-right"></i></a>
                                    <a href="{{ route('aquaheart.products.edit', $product) }}" class="icon-link b-gray"><i data-lucide="edit-2"></i></a>
                                    <form method="POST" action="{{ route('aquaheart.products.destroy', $product) }}" style="display: inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="icon-link b-red" onclick="return confirm('Delete this product?')">
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
            {{ $products->links() }}
        </div>
    @else
        <div class="blank-slate">
            <div class="slate-icon"><i data-lucide="package-2"></i></div>
            <h4>No Products Yet</h4>
            <p>Add bottled water offerings to start tracking sales and inventory.</p>
        </div>
    @endif
</div>

@push('styles')
<style>
    .alert-success-minimal { display: flex; align-items: center; gap: 10px; background: #f0fdf4; border: 1px solid #bbf7d0; color: #166534; padding: 12px 20px; border-radius: 10px; font-weight: 700; font-size: 0.85rem; margin-bottom: 24px; }
    .summary-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 24px; margin-bottom: 24px; }
    .summary-card { display: flex; flex-direction: column; gap: 8px; }
    .summary-label { font-size: 0.75rem; color: var(--text-muted); text-transform: uppercase; font-weight: 800; }
    .summary-value { font-size: 1.6rem; color: var(--primary); }
    .table-card { padding: 0; }
    .modern-table { width: 100%; border-collapse: collapse; }
    .modern-table th, .modern-table td { padding: 16px 24px; border-bottom: 1px solid var(--border); text-align: left; }
    .modern-table th { font-size: 0.75rem; font-weight: 800; color: var(--text-muted); text-transform: uppercase; }
    .product-name { font-weight: 700; color: var(--primary); }
    .product-desc { margin-top: 4px; color: var(--text-muted); font-size: 0.8rem; }
    .stock-pill, .status-badge { display: inline-flex; padding: 6px 10px; border-radius: 999px; font-size: 0.75rem; font-weight: 800; }
    .stock-pill { background: #dcfce7; color: #166534; }
    .stock-pill.low { background: #fee2e2; color: #b91c1c; }
    .status-badge.active { background: #dbeafe; color: #1d4ed8; }
    .status-badge.inactive { background: #e5e7eb; color: #4b5563; }
    .action-row { display: flex; gap: 8px; justify-content: flex-end; }
    .icon-link { width: 32px; height: 32px; border-radius: 8px; display: inline-flex; align-items: center; justify-content: center; text-decoration: none; border: none; cursor: pointer; }
    .icon-link.b-blue { color: #3b82f6; background: #eff6ff; }
    .icon-link.b-gray { color: #64748b; background: #f1f5f9; }
    .icon-link.b-red { color: #ef4444; background: #fef2f2; }
    .table-footer { padding: 24px; }
    .blank-slate { padding: 72px 32px; text-align: center; color: var(--text-muted); }
    .slate-icon { margin-bottom: 16px; }
    @media (max-width: 1024px) { .summary-grid { grid-template-columns: 1fr; } }
</style>
@endpush
@endsection
