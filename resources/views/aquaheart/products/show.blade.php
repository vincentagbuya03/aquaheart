@extends('layouts.aquaheart')

@section('title', 'Product Details')
@section('page_title', $product->name)
@section('page_subtitle', 'Review pricing, inventory level, and transaction usage for this product.')

@section('page_actions')
<a href="{{ route('aquaheart.products.edit', $product) }}" class="btn-primary">
    <i data-lucide="edit-2" size="18"></i>
    Edit Product
</a>
@endsection

@section('content')
<div class="detail-grid">
    <div class="card detail-card">
        <div class="detail-row">
            <span class="label">Unit Price</span>
            <strong>₱ {{ number_format($product->price, 2) }}</strong>
        </div>
        <div class="detail-row">
            <span class="label">Stock Quantity</span>
            <strong>{{ $product->stock_quantity }}</strong>
        </div>
        <div class="detail-row">
            <span class="label">Reorder Level</span>
            <strong>{{ $product->reorder_level }}</strong>
        </div>
        <div class="detail-row">
            <span class="label">Status</span>
            <strong>{{ $product->is_active ? 'Active' : 'Inactive' }}</strong>
        </div>
        <div class="detail-row">
            <span class="label">Transactions</span>
            <strong>{{ $product->refills_count }}</strong>
        </div>
    </div>
    <div class="card detail-card">
        <span class="label">Description</span>
        <p class="description">{{ $product->description ?: 'No description provided.' }}</p>
        <a href="{{ route('aquaheart.products.index') }}" class="secondary-link">Back to product list</a>
    </div>
</div>

@push('styles')
<style>
    .detail-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 24px; }
    .detail-card { display: flex; flex-direction: column; gap: 20px; }
    .detail-row { display: flex; justify-content: space-between; gap: 16px; padding-bottom: 16px; border-bottom: 1px solid var(--border); }
    .label { color: var(--text-muted); font-size: 0.8rem; font-weight: 800; text-transform: uppercase; }
    .description { color: var(--text-main); line-height: 1.7; }
    .secondary-link { color: var(--accent); text-decoration: none; font-weight: 700; }
    @media (max-width: 900px) { .detail-grid { grid-template-columns: 1fr; } }
</style>
@endpush
@endsection
