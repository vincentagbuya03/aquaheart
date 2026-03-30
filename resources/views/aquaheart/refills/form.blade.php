@extends('layouts.aquaheart')

@section('title', isset($refill) ? 'Edit Refill' : 'Record Refill')
@section('page_title', isset($refill) ? 'Edit Transaction' : 'Record New Sale / Refill')
@section('page_subtitle', 'Capture customer, product, quantity, payment, and service details.')

@section('content')
<div class="form-container-boxed">
    <div class="card form-card">
        <form method="POST" action="{{ isset($refill) ? route('aquaheart.refills.update', $refill) : route('aquaheart.refills.store') }}">
            @csrf
            @if (isset($refill))
                @method('PUT')
            @endif

            <div class="form-grid">
                <div class="form-group full">
                    <label class="field-label">Customer Name</label>
                    <div style="position: relative;">
                        <input
                            type="text"
                            id="customer_name"
                            name="customer_name"
                            class="form-input"
                            value="{{ old('customer_name', isset($refill) ? $refill->customer->name : '') }}"
                            placeholder="Type customer name or select from suggestions..."
                            autocomplete="off"
                            required
                        >
                        <input type="hidden" id="customer_id" name="customer_id" value="{{ old('customer_id', $refill->customer_id ?? '') }}">
                        <ul id="customer_suggestions" class="suggestions-list"></ul>
                    </div>
                    @error('customer_name') <span class="error-msg">{{ $message }}</span> @enderror
                </div>

                <div class="form-group">
                    <label class="field-label">Product Type</label>
                    <select id="product_id" name="product_id" class="form-input" required>
                        <option value="">Select a product</option>
                        @foreach ($products as $product)
                            <option
                                value="{{ $product->id }}"
                                data-price="{{ $product->price }}"
                                {{ old('product_id', $refill->product_id ?? '') == $product->id ? 'selected' : '' }}
                            >
                                {{ $product->name }} - PHP {{ number_format($product->price, 2) }} - {{ $product->stock_quantity }} in stock
                            </option>
                        @endforeach
                    </select>
                    @error('product_id') <span class="error-msg">{{ $message }}</span> @enderror
                </div>

                <div class="form-group">
                    <label class="field-label">Quantity</label>
                    <input type="number" id="quantity" name="quantity" class="form-input" value="{{ old('quantity', $refill->quantity ?? 1) }}" min="1" required>
                    @error('quantity') <span class="error-msg">{{ $message }}</span> @enderror
                </div>

                <div class="form-group">
                    <label class="field-label">Unit Price</label>
                    <input type="number" id="unit_price" name="unit_price" class="form-input" value="{{ old('unit_price', $refill->unit_price ?? '') }}" step="0.01" min="0" required>
                    @error('unit_price') <span class="error-msg">{{ $message }}</span> @enderror
                </div>

                <div class="form-group">
                    <label class="field-label">Computed Total</label>
                    <input type="text" id="computed_total" class="form-input" value="PHP 0.00" readonly>
                </div>

                <div class="form-group">
                    <label class="field-label">Payment Status</label>
                    <select name="payment_status" class="form-input" required>
                        @foreach (['paid' => 'Paid', 'unpaid' => 'Unpaid', 'partial' => 'Partial'] as $value => $label)
                            <option value="{{ $value }}" {{ old('payment_status', $refill->payment_status ?? 'paid') === $value ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                    @error('payment_status') <span class="error-msg">{{ $message }}</span> @enderror
                </div>

                <div class="form-group">
                    <label class="field-label">Service Type</label>
                    <select name="service_type" class="form-input" required>
                        @foreach (['walk_in' => 'Walk-in', 'delivery' => 'Delivery', 'pickup' => 'Pickup'] as $value => $label)
                            <option value="{{ $value }}" {{ old('service_type', $refill->service_type ?? 'walk_in') === $value ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                    @error('service_type') <span class="error-msg">{{ $message }}</span> @enderror
                </div>

                <div class="form-group">
                    <label class="field-label">Processing Date</label>
                    <input type="date" name="refill_date" class="form-input" value="{{ old('refill_date', isset($refill) ? $refill->refill_date->format('Y-m-d') : now()->format('Y-m-d')) }}" required>
                    @error('refill_date') <span class="error-msg">{{ $message }}</span> @enderror
                </div>

                <div class="form-group full">
                    <label class="field-label">Notes</label>
                    <textarea name="notes" rows="4" class="form-input" placeholder="Optional delivery instructions or transaction notes">{{ old('notes', $refill->notes ?? '') }}</textarea>
                    @error('notes') <span class="error-msg">{{ $message }}</span> @enderror
                </div>
            </div>

            <div class="form-footer">
                <a href="{{ route('aquaheart.refills.index') }}" class="btn-cancel">Cancel changes</a>
                <button type="submit" class="btn-primary">
                    <i data-lucide="save" size="18"></i>
                    {{ isset($refill) ? 'Commit Updates' : 'Confirm Transaction' }}
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    const customers = @json($customers);
    const customerNameInput = document.getElementById('customer_name');
    const customerIdInput = document.getElementById('customer_id');
    const customerSuggestions = document.getElementById('customer_suggestions');
    const productSelect = document.getElementById('product_id');
    const quantityInput = document.getElementById('quantity');
    const unitPriceInput = document.getElementById('unit_price');
    const computedTotalInput = document.getElementById('computed_total');

    customerNameInput.addEventListener('input', function (event) {
        const searchTerm = event.target.value.toLowerCase().trim();
        customerIdInput.value = '';

        if (searchTerm.length === 0) {
            customerSuggestions.style.display = 'none';
            return;
        }

        const filtered = customers.filter(customer => customer.name.toLowerCase().includes(searchTerm));

        if (filtered.length === 0) {
            customerSuggestions.innerHTML = '<li class="suggestion-item empty">No match found. A new customer will be created.</li>';
            customerSuggestions.style.display = 'block';
            return;
        }

        customerSuggestions.innerHTML = filtered.map(customer =>
            `<li class="suggestion-item" onclick="selectCustomer('${customer.id}', '${customer.name.replace(/'/g, "\\'")}')">${customer.name}</li>`
        ).join('');
        customerSuggestions.style.display = 'block';
    });

    function selectCustomer(id, name) {
        customerNameInput.value = name;
        customerIdInput.value = id;
        customerSuggestions.style.display = 'none';
    }

    function syncUnitPriceFromProduct() {
        const selected = productSelect.options[productSelect.selectedIndex];
        if (!selected || !selected.dataset.price) {
            return;
        }

        if (unitPriceInput.value === '' || document.activeElement !== unitPriceInput) {
            unitPriceInput.value = Number(selected.dataset.price).toFixed(2);
        }
        updateComputedTotal();
    }

    function updateComputedTotal() {
        const quantity = Number(quantityInput.value || 0);
        const unitPrice = Number(unitPriceInput.value || 0);
        const total = quantity * unitPrice;
        computedTotalInput.value = `PHP ${total.toFixed(2)}`;
    }

    productSelect.addEventListener('change', syncUnitPriceFromProduct);
    quantityInput.addEventListener('input', updateComputedTotal);
    unitPriceInput.addEventListener('input', updateComputedTotal);

    document.addEventListener('click', function (event) {
        if (event.target !== customerNameInput) {
            customerSuggestions.style.display = 'none';
        }
    });

    syncUnitPriceFromProduct();
</script>

@push('styles')
<style>
    .form-container-boxed { max-width: 860px; margin: 0 auto; }
    .form-card { padding: 40px; border-radius: 20px; }
    .form-grid { display: grid; grid-template-columns: repeat(2, 1fr); gap: 24px; margin-bottom: 40px; }
    .form-group.full { grid-column: span 2; }
    .field-label { display: block; font-size: 0.8rem; font-weight: 800; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 10px; }
    .form-input { width: 100%; padding: 12px 16px; border: 1px solid var(--border); border-radius: 12px; font-family: inherit; font-size: 0.9rem; background: var(--bg); color: var(--primary); resize: vertical; }
    .form-input:focus { outline: none; border-color: var(--accent); background: white; box-shadow: 0 0 0 4px var(--accent-soft); }
    .form-footer { display: flex; justify-content: flex-end; align-items: center; gap: 24px; padding-top: 30px; border-top: 1px solid var(--border); }
    .btn-cancel { text-decoration: none; color: var(--text-muted); font-size: 0.9rem; font-weight: 700; }
    .error-msg { font-size: 0.75rem; color: #dc2626; font-weight: 700; margin-top: 6px; display: block; }
    .suggestions-list { position: absolute; top: 100%; left: 0; right: 0; background: white; border: 1px solid var(--border); border-top: none; list-style: none; max-height: 220px; overflow-y: auto; display: none; z-index: 10; }
    .suggestion-item { padding: 12px 16px; cursor: pointer; border-bottom: 1px solid #f1f5f9; }
    .suggestion-item:hover { background: #f8fafc; }
    .suggestion-item.empty { color: var(--text-muted); cursor: default; }
    @media (max-width: 768px) { .form-grid { grid-template-columns: 1fr; } .form-group.full { grid-column: span 1; } }
</style>
@endpush
@endsection
