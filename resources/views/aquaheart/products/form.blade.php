@extends('layouts.aquaheart')

@section('title', isset($product) ? 'Edit Product' : 'Add Product')
@section('page_title', isset($product) ? 'Update Product Catalog' : 'Create Product')
@section('page_subtitle', 'Capture pricing and stock values for refill inventory tracking.')

@section('content')
<div class="form-container-boxed">
    <div class="card form-card">
        <form method="POST" action="{{ isset($product) ? route('aquaheart.products.update', $product) : route('aquaheart.products.store') }}">
            @csrf
            @if (isset($product))
                @method('PUT')
            @endif

            <div class="form-grid">
                <div class="form-group full">
                    <label class="field-label">Product Name</label>
                    <input type="text" name="name" class="form-input" value="{{ old('name', $product->name ?? '') }}" required>
                    @error('name') <span class="error-msg">{{ $message }}</span> @enderror
                </div>
                <div class="form-group">
                    <label class="field-label">Unit Price</label>
                    <input type="number" name="price" class="form-input" value="{{ old('price', $product->price ?? '') }}" step="0.01" min="0" required>
                    @error('price') <span class="error-msg">{{ $message }}</span> @enderror
                </div>
                <div class="form-group">
                    <label class="field-label">Stock Quantity</label>
                    <input type="number" name="stock_quantity" class="form-input" value="{{ old('stock_quantity', $product->stock_quantity ?? 0) }}" min="0" required>
                    @error('stock_quantity') <span class="error-msg">{{ $message }}</span> @enderror
                </div>
                <div class="form-group">
                    <label class="field-label">Reorder Level</label>
                    <input type="number" name="reorder_level" class="form-input" value="{{ old('reorder_level', $product->reorder_level ?? 10) }}" min="0" required>
                    @error('reorder_level') <span class="error-msg">{{ $message }}</span> @enderror
                </div>
                <div class="form-group toggle-group">
                    <label class="field-label">Availability</label>
                    <label class="toggle">
                        <input type="checkbox" name="is_active" value="1" {{ old('is_active', $product->is_active ?? true) ? 'checked' : '' }}>
                        <span>Active product</span>
                    </label>
                </div>
                <div class="form-group full">
                    <label class="field-label">Description</label>
                    <textarea name="description" rows="4" class="form-input">{{ old('description', $product->description ?? '') }}</textarea>
                    @error('description') <span class="error-msg">{{ $message }}</span> @enderror
                </div>
            </div>

            <div class="form-footer">
                <a href="{{ route('aquaheart.products.index') }}" class="btn-cancel">Cancel</a>
                <button type="submit" class="btn-primary">
                    <i data-lucide="save" size="18"></i>
                    {{ isset($product) ? 'Save Product' : 'Create Product' }}
                </button>
            </div>
        </form>
    </div>
</div>

@push('styles')
<style>
    .form-container-boxed { max-width: 720px; margin: 0 auto; }
    .form-card { padding: 40px; border-radius: 20px; }
    .form-grid { display: grid; grid-template-columns: repeat(2, 1fr); gap: 24px; margin-bottom: 40px; }
    .form-group.full { grid-column: span 2; }
    .field-label { display: block; font-size: 0.8rem; font-weight: 800; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 10px; }
    .form-input { width: 100%; padding: 12px 16px; border: 1px solid var(--border); border-radius: 12px; font-family: inherit; font-size: 0.9rem; background: var(--bg); color: var(--primary); }
    .form-input:focus { outline: none; border-color: var(--accent); background: white; box-shadow: 0 0 0 4px var(--accent-soft); }
    .toggle-group { display: flex; flex-direction: column; justify-content: flex-end; }
    .toggle { display: inline-flex; align-items: center; gap: 10px; font-weight: 600; color: var(--primary); }
    .form-footer { display: flex; justify-content: flex-end; align-items: center; gap: 24px; padding-top: 30px; border-top: 1px solid var(--border); }
    .btn-cancel { text-decoration: none; color: var(--text-muted); font-size: 0.9rem; font-weight: 700; }
    .error-msg { font-size: 0.75rem; color: #dc2626; font-weight: 700; margin-top: 6px; display: block; }
    @media (max-width: 768px) { .form-grid { grid-template-columns: 1fr; } .form-group.full { grid-column: span 1; } }
</style>
@endpush
@endsection
