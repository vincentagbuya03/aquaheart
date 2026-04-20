@extends('layouts.aquaheart')

@section('title', isset($refill) ? 'Edit Transaction' : 'Record Transaction')

@section('content')
@php
    $editingRefill = $refill ?? null;
@endphp

<div class="transaction-header">
    <div class="breadcrumb">ADMIN PORTAL &bull; SALES DESK</div>
    <h1 class="page-title">{{ $editingRefill ? 'Edit' : 'Record' }}<span>Transaction</span></h1>
    <p class="page-subtitle">Capture detailed sales data including customer profiles, product metrics, and payment status.</p>
</div>

<div class="form-wrapper">
    <div class="card premium-form-card">
        <form method="POST" action="{{ isset($refill) ? route('aquaheart.refills.update', $refill) : route('aquaheart.refills.store') }}">
            @csrf
            @if (isset($refill))
                @method('PUT')
            @endif

            <div class="form-section">
                <h3 class="section-divider"><span>CUSTOMER INFORMATION</span></h3>
                <div class="form-grid">
                    <div class="form-group full">
                        <div class="customer-search-wrapper">
                            <label class="field-label">Search or Register Customer</label>
                            <div class="input-with-icon search-active">
                                <i data-lucide="user-check" size="18"></i>
                                <input
                                    type="text"
                                    id="customer_name"
                                    name="customer_name"
                                    class="form-input customer-search-input"
                                    value="{{ old('customer_name', $editingRefill ? ($editingRefill->customer->name ?? '') : '') }}"
                                    placeholder="Type customer name to search or create new..."
                                    autocomplete="off"
                                    required
                                >
                                <i id="search_indicator" class="search-spinner" data-lucide="loader-2" size="18" style="display: none;"></i>
                            </div>
                            <div class="search-helper-text">
                                💡 <strong>Tip:</strong> Start typing to see existing customers. Select from the dropdown to avoid duplicates. If no match, a new profile will be created.
                            </div>
                            <input type="hidden" id="customer_id" name="customer_id" value="{{ old('customer_id', $editingRefill?->customer_id ?? '') }}">
                            <ul id="customer_suggestions" class="suggestions-list"></ul>
                            @error('customer_name') <span class="error-msg">{{ $message }}</span> @enderror
                        </div>
                    </div>
                </div>
            </div>

            <div class="form-section">
                <h3 class="section-divider"><span>TRANSACTION DETAILS</span></h3>
                <div class="form-grid">
                    <div class="form-group">
                        <label class="field-label">Inventory Item</label>
                        <div class="input-with-icon">
                            <i data-lucide="box" size="18"></i>
                            <select id="product_id" name="product_id" class="form-input" required>
                                <option value="">Select a product</option>
                                @foreach ($products as $product)
                                    <option
                                        value="{{ $product->id }}"
                                        data-price="{{ $product->price }}"
                                        data-stock="{{ $product->stock_quantity }}"
                                        data-reorder="{{ $product->reorder_level }}"
                                        {{ old('product_id', $editingRefill?->product_id ?? '') == $product->id ? 'selected' : '' }}
                                    >
                                        {{ $product->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div id="product_status" class="product-status">Ready for selection.</div>
                        @error('product_id') <span class="error-msg">{{ $message }}</span> @enderror
                    </div>

                    <div class="form-group">
                        <label class="field-label">Quantity</label>
                        <div class="input-with-icon">
                            <i data-lucide="plus-minus" size="18"></i>
                            <input type="number" id="quantity" name="quantity" class="form-input" value="{{ old('quantity', $editingRefill?->quantity ?? 1) }}" min="1" required>
                        </div>
                        @error('quantity') <span class="error-msg">{{ $message }}</span> @enderror
                    </div>

                    <div class="form-group">
                        <label class="field-label">Unit Price (PHP)</label>
                        <div class="input-with-icon">
                            <i data-lucide="tag" size="18"></i>
                            <input type="number" id="unit_price" name="unit_price" class="form-input" value="{{ old('unit_price', $editingRefill?->unit_price ?? '') }}" step="0.01" min="0" required>
                        </div>
                        @error('unit_price') <span class="error-msg">{{ $message }}</span> @enderror
                    </div>

                    <div class="form-group">
                        <label class="field-label">Computed Total</label>
                        <div class="computed-total-box">
                            <span class="currency">PHP</span>
                            <input type="text" id="computed_total" class="total-display" value="0.00" readonly>
                        </div>
                    </div>
                </div>
            </div>

            <div class="form-section">
                <h3 class="section-divider"><span>SERVICE & STATUS</span></h3>
                <div class="form-grid">
                    <div class="form-group">
                        <label class="field-label">Payment Status</label>
                        <div class="input-with-icon">
                            <i data-lucide="banknote" size="18"></i>
                            <select id="payment_status_select" name="payment_status" class="form-input" required>
                                @foreach (['paid' => 'Paid', 'unpaid' => 'Unpaid', 'partial' => 'Partial'] as $value => $label)
                                    <option value="{{ $value }}" {{ old('payment_status', $editingRefill?->payment_status ?? 'paid') === $value ? 'selected' : '' }}>{{ $label }}</option>
                                @endforeach
                            </select>
                        </div>
                        @error('payment_status') <span class="error-msg">{{ $message }}</span> @enderror
                    </div>

                    <!-- Partial Payment Fields - Show only when partial is selected -->
                    <div class="form-group partial-payment-field" id="paid_amount_field" style="display: none;">
                        <label class="field-label">Amount Already Paid (PHP)</label>
                        <div class="input-with-icon">
                            <i data-lucide="check-check" size="18"></i>
                            <input type="number" id="paid_amount" name="paid_amount" class="form-input" step="0.01" min="0" value="{{ old('paid_amount', $editingRefill?->paid_amount ?? '') }}" placeholder="Enter paid amount">
                        </div>
                        @error('paid_amount') <span class="error-msg">{{ $message }}</span> @enderror
                    </div>

                    <div class="form-group partial-payment-field" id="partial_amount_field" style="display: none;">
                        <label class="field-label">
                            Amount Still Owed (PHP)
                            <span class="auto-calc-badge">Auto-calculated</span>
                        </label>
                        <div class="input-with-icon">
                            <i data-lucide="history" size="18"></i>
                            <input type="number" id="partial_amount" name="partial_amount" class="form-input" step="0.01" min="0" value="{{ old('partial_amount', $editingRefill?->partial_amount ?? '') }}" placeholder="Auto-calculated" readonly>
                        </div>
                        @error('partial_amount') <span class="error-msg">{{ $message }}</span> @enderror
                    </div>

                    <div id="partial_summary" class="partial-payment-summary" style="display: none;">
                        <div class="summary-box">
                            <div class="summary-item">
                                <span class="summary-label">Total Amount</span>
                                <span id="summary_total" class="summary-value">₱0.00</span>
                            </div>
                            <div class="summary-divider"></div>
                            <div class="summary-item">
                                <span class="summary-label">Already Paid</span>
                                <span id="summary_paid" class="summary-value paid">₱0.00</span>
                            </div>
                            <div class="summary-item">
                                <span class="summary-label">Still Owed</span>
                                <span id="summary_owed" class="summary-value owed">₱0.00</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="form-section">
                <h3 class="section-divider"><span>SERVICE & DELIVERY</span></h3>
                <div class="form-grid">
                    <div class="form-group">
                        <label class="field-label">Service Distribution</label>
                        <div class="input-with-icon">
                            <i data-lucide="truck" size="18"></i>
                            <select name="service_type" class="form-input" required>
                                @foreach (['walk_in' => 'Walk-in (Counter)', 'delivery' => 'Home Delivery', 'pickup' => 'Scheduled Pickup'] as $value => $label)
                                    <option value="{{ $value }}" {{ old('service_type', $editingRefill?->service_type ?? 'walk_in') === $value ? 'selected' : '' }}>{{ $label }}</option>
                                @endforeach
                            </select>
                        </div>
                        @error('service_type') <span class="error-msg">{{ $message }}</span> @enderror
                    </div>

                    <div class="form-group">
                        <label class="field-label">Operational Date</label>
                        <div class="input-with-icon">
                            <i data-lucide="calendar-range" size="18"></i>
                            <input type="date" name="refill_date" class="form-input" value="{{ old('refill_date', $editingRefill ? (optional($editingRefill->refill_date)->format('Y-m-d') ?? optional($editingRefill->created_at)->format('Y-m-d') ?? now()->format('Y-m-d')) : now()->format('Y-m-d')) }}" required>
                        </div>
                        @error('refill_date') <span class="error-msg">{{ $message }}</span> @enderror
                    </div>

                    <div class="form-group full">
                        <label class="field-label">Operational Notes</label>
                        <textarea name="notes" rows="3" class="form-input textarea" placeholder="Add specific delivery instructions or quality check notes...">{{ old('notes', $editingRefill?->notes ?? '') }}</textarea>
                        @error('notes') <span class="error-msg">{{ $message }}</span> @enderror
                    </div>
                </div>
            </div>

            <div class="form-footer">
                <a href="{{ route('aquaheart.refills.index') }}" class="btn-cancel">Discard Changes</a>
                <button type="submit" class="btn-premium">
                    <i data-lucide="check-circle"></i>
                    {{ $editingRefill ? 'Update Transaction' : 'Record Transaction' }}
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const customerNameInput = document.getElementById('customer_name');
        const customerIdInput = document.getElementById('customer_id');
        const customerSuggestions = document.getElementById('customer_suggestions');
        const searchIndicator = document.getElementById('search_indicator');
        const productSelect = document.getElementById('product_id');
        const quantityInput = document.getElementById('quantity');
        const unitPriceInput = document.getElementById('unit_price');
        const computedTotalInput = document.getElementById('computed_total');
        const productStatus = document.getElementById('product_status');
        const paymentStatusSelect = document.getElementById('payment_status_select');
        const paidAmountInput = document.getElementById('paid_amount');
        const partialAmountInput = document.getElementById('partial_amount');
        const paidAmountField = document.getElementById('paid_amount_field');
        const partialAmountField = document.getElementById('partial_amount_field');
        const partialSummary = document.getElementById('partial_summary');
        let customerSearchTimeout = null;

        // Payment status field visibility and summary
        function updatePartialPaymentFields() {
            const isPartial = paymentStatusSelect.value === 'partial';
            
            if (isPartial) {
                paidAmountField.style.display = 'block';
                partialAmountField.style.display = 'block';
                partialSummary.style.display = 'block';
            } else {
                paidAmountField.style.display = 'none';
                partialAmountField.style.display = 'none';
                partialSummary.style.display = 'none';
            }
        }

        function updatePartialSummary() {
            const total = Number(computedTotalInput.value.replace(/[^\d.-]/g, '')) || 0;
            const paidAmount = Number(paidAmountInput.value) || 0;
            
            // Auto-calculate remaining amount
            const remainingAmount = Math.max(0, total - paidAmount);
            partialAmountInput.value = remainingAmount.toFixed(2);

            document.getElementById('summary_total').textContent = '₱' + total.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
            document.getElementById('summary_paid').textContent = '₱' + paidAmount.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
            document.getElementById('summary_owed').textContent = '₱' + remainingAmount.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 });

            // Visual feedback if paid amount exceeds total
            const summary = document.getElementById('partial_summary');
            if (paidAmount > total && total > 0) {
                summary.classList.add('mismatch');
            } else {
                summary.classList.remove('mismatch');
            }
        }

        paymentStatusSelect.addEventListener('change', updatePartialPaymentFields);
        paidAmountInput.addEventListener('input', updatePartialSummary);

        function renderCustomerSuggestions(items, emptyMessage) {
            searchIndicator.style.display = 'none';
            
            if (!items.length) {
                customerSuggestions.innerHTML = `<li class="suggestion-item empty"><i data-lucide="user-plus" size="16"></i> ${emptyMessage}</li>`;
                customerSuggestions.style.display = 'block';
                return;
            }

            customerSuggestions.innerHTML = items.map(customer => {
                const phone = customer.phone ? ` <span class="suggestion-phone">📞 ${customer.phone}</span>` : '';
                const safeName = customer.name.replace(/'/g, "\\'");
                return `<li class="suggestion-item" onclick="selectCustomer('${customer.id}', '${safeName}')">
                    <div class="s-avatar">${customer.name.charAt(0).toUpperCase()}</div>
                    <div class="s-info">
                        <span class="s-name">${customer.name}</span>
                        ${phone}
                    </div>
                    <div class="s-select-hint"><i data-lucide="mouse-pointer-2" size="14"></i> Click to select</div>
                </li>`;
            }).join('');
            customerSuggestions.style.display = 'block';
            // Reinitialize lucide icons for the new elements
            if (window.lucide) {
                window.lucide.createIcons();
            }
        }

        window.selectCustomer = function(id, name) {
            customerNameInput.value = name;
            customerIdInput.value = id;
            customerSuggestions.style.display = 'none';
            customerNameInput.closest('.input-with-icon').classList.add('customer-selected');
        }

        customerNameInput.addEventListener('input', function (event) {
            const searchTerm = event.target.value.toLowerCase().trim();
            customerIdInput.value = '';
            customerNameInput.closest('.input-with-icon').classList.remove('customer-selected');

            if (searchTerm.length === 0) {
                customerSuggestions.style.display = 'none';
                searchIndicator.style.display = 'none';
                return;
            }

            // Show loading spinner
            if (searchTerm.length > 0) {
                searchIndicator.style.display = 'block';
            }

            window.clearTimeout(customerSearchTimeout);
            customerSearchTimeout = window.setTimeout(async function () {
                try {
                    const response = await fetch(`{{ route('aquaheart.customers.search') }}?term=${encodeURIComponent(searchTerm)}`, {
                        headers: {
                            'Accept': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    });

                    const payload = await response.json();
                    renderCustomerSuggestions(payload.data || [], '✨ No existing customers found. A new profile will be created when you submit.');
                } catch (error) {
                    searchIndicator.style.display = 'none';
                    customerSuggestions.style.display = 'none';
                }
            }, 300);
        });

        function syncUnitPriceFromProduct() {
            const selected = productSelect.options[productSelect.selectedIndex];
            if (!selected || !selected.dataset.price) {
                productStatus.textContent = 'Ready for selection.';
                productStatus.className = 'product-status';
                return;
            }

            if (unitPriceInput.value === '' || document.activeElement !== unitPriceInput) {
                unitPriceInput.value = Number(selected.dataset.price).toFixed(2);
            }

            const stock = Number(selected.dataset.stock || 0);
            const reorder = Number(selected.dataset.reorder || 0);
            
            if (stock <= 0) {
                productStatus.textContent = `CRITICAL: Out of stock.`;
                productStatus.className = 'product-status danger';
            } else if (stock <= reorder) {
                productStatus.textContent = `WARNING: Low stock (${stock} left).`;
                productStatus.className = 'product-status warning';
            } else {
                productStatus.textContent = `HEALTHY: ${stock} units available.`;
                productStatus.className = 'product-status success';
            }

            updateComputedTotal();
        }

        function updateComputedTotal() {
            const quantity = Number(quantityInput.value || 0);
            const unitPrice = Number(unitPriceInput.value || 0);
            const total = quantity * unitPrice;
            computedTotalInput.value = total.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
            updatePartialSummary();
        }

        productSelect.addEventListener('change', syncUnitPriceFromProduct);
        quantityInput.addEventListener('input', updateComputedTotal);
        unitPriceInput.addEventListener('input', updateComputedTotal);

        // Initialize on page load
        updatePartialPaymentFields();
        updatePartialSummary();

        document.addEventListener('click', function (event) {
            if (!customerNameInput.contains(event.target) && !customerSuggestions.contains(event.target)) {
                customerSuggestions.style.display = 'none';
                searchIndicator.style.display = 'none';
            }
        });

        syncUnitPriceFromProduct();
    });
</script>

@push('styles')
<style>
    /* Hide Default Layout Header */
    .section-header { display: none !important; }

    .transaction-header { margin-bottom: 32px; }
    .breadcrumb { font-size: 0.7rem; font-weight: 800; color: #0284c7; text-transform: uppercase; letter-spacing: 1.5px; margin-bottom: 8px; }
    .page-title { font-size: 2.2rem; font-weight: 800; color: var(--primary); letter-spacing: -1.5px; }
    .page-title span { color: #0284c7; }
    .page-subtitle { color: var(--text-muted); font-size: 0.95rem; margin-top: 4px; max-width: 600px; }

    .form-wrapper { max-width: 900px; margin-bottom: 60px; }
    .premium-form-card { padding: 48px; border-radius: 32px; box-shadow: 0 10px 40px rgba(0,0,0,0.03); border: none; }
    
    .form-section { margin-bottom: 40px; }
    .section-divider { display: flex; align-items: center; margin-bottom: 24px; font-size: 0.65rem; font-weight: 800; color: #94a3b8; letter-spacing: 1px; }
    .section-divider::after { content: ""; flex: 1; height: 1px; background: #f1f5f9; margin-left: 16px; }

    .form-grid { display: grid; grid-template-columns: repeat(2, 1fr); gap: 24px 32px; }
    .form-group.full { grid-column: span 2; }
    
    .field-label { display: block; font-size: 0.75rem; font-weight: 800; color: var(--primary); text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 12px; display: flex; align-items: center; justify-content: space-between; }
    
    .auto-calc-badge {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        background: #f0f9ff;
        color: #0284c7;
        padding: 4px 8px;
        border-radius: 6px;
        font-size: 0.65rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin: 0;
    }
    
    .auto-calc-badge::before {
        content: "⚡";
    }
    
    .input-with-icon { 
        display: flex; 
        align-items: stretch; 
        background: #f8fafc;
        border: 1.5px solid #f1f5f9;
        border-radius: 14px;
        transition: all 0.2s;
        overflow: hidden;
    }

    .input-with-icon:focus-within {
        border-color: var(--accent);
        background: white;
        box-shadow: 0 0 0 4px var(--accent-soft);
    }
    
    .input-with-icon i { 
        width: 48px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: var(--accent); 
        background: white;
        border-right: 1.5px solid #f1f5f9;
        pointer-events: none; 
        stroke-width: 2.5; 
        flex-shrink: 0;
    }

    .input-with-icon:focus-within i {
        border-right-color: var(--accent);
    }
    
    /* Customer Search Wrapper */
    .customer-search-wrapper {
        position: relative;
        z-index: 50;
    }

    .search-active {
        position: relative;
    }

    .search-spinner {
        position: absolute !important;
        right: 16px !important;
        left: auto !important;
        animation: spin 1s linear infinite;
        color: #0284c7;
    }

    @keyframes spin {
        from { transform: rotate(0deg); }
        to { transform: rotate(360deg); }
    }

    .search-helper-text {
        display: block;
        margin-top: 8px;
        padding: 10px 12px;
        background: #f0f9ff;
        border: 1px solid #bae6fd;
        border-radius: 8px;
        font-size: 0.8rem;
        color: #0369a1;
        line-height: 1.4;
    }

    .search-helper-text strong {
        font-weight: 700;
        color: #0284c7;
    }
    
    .form-input { 
        width: 100%; 
        padding: 12px 16px; 
        border: none; 
        font-family: inherit; 
        font-size: 0.95rem; 
        background: transparent; 
        color: var(--primary); 
        outline: none; 
        flex: 1;
        transition: all 0.2s;
    }

    select.form-input {
        appearance: none;
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='16' height='16' viewBox='0 0 24 24' fill='none' stroke='%2394a3b8' stroke-width='2.5' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpath d='m6 9 6 6 6-6'/%3E%3C/svg%3E");
        background-repeat: no-repeat;
        background-position: right 16px center;
        background-size: 16px;
        padding-right: 44px;
        cursor: pointer;
    }

    .form-input:focus { background: transparent; }
    .form-input:read-only { color: #64748b; cursor: not-allowed; }
    .form-input:read-only:focus { box-shadow: none; }
    .form-input.customer-search-input { padding-right: 48px; }
    .form-input.customer-search-input { padding-right: 48px; }
    
    .input-with-icon.customer-selected { border-color: #059669; background: #f0fdf4; }
    .input-with-icon.customer-selected i { border-right-color: #059669; color: #059669; }

    .form-input.textarea { padding-left: 16px; resize: vertical; }

    .product-status { margin-top: 8px; font-size: 0.75rem; font-weight: 700; color: var(--text-muted); padding-left: 4px; }
    .product-status.success { color: #059669; }
    .product-status.warning { color: #d97706; }
    .product-status.danger { color: #dc2626; }

    .computed-total-box { 
        display: flex; 
        align-items: stretch; 
        background: #f0f9ff; 
        border-radius: 14px; 
        border: 1.5px dashed #bae6fd; 
        overflow: hidden;
        height: 48px;
    }
    .computed-total-box .currency { 
        width: 48px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.75rem; 
        font-weight: 800; 
        color: #0284c7; 
        background: white;
        border-right: 1.5px dashed #bae6fd;
    }
    .total-display { 
        border: none; 
        background: transparent; 
        font-size: 1.4rem; 
        font-weight: 800; 
        color: var(--primary); 
        outline: none; 
        width: 100%; 
        padding: 0 16px; 
        pointer-events: none; 
        display: flex;
        align-items: center;
    }

    .suggestions-list { 
        position: absolute; 
        top: calc(100% + 8px); 
        left: 0; 
        right: 0; 
        background: white; 
        border-radius: 16px; 
        border: 1.5px solid #e2e8f0; 
        list-style: none; 
        max-height: 350px; 
        overflow-y: auto; 
        display: none; 
        z-index: 100; 
        box-shadow: 0 20px 40px rgba(0,0,0,0.12); 
        margin-top: 8px;
        margin-bottom: 0;
        padding: 0;
    }

    .suggestion-item { 
        padding: 14px 16px; 
        cursor: pointer; 
        border-bottom: 1px solid #f1f5f9; 
        display: flex; 
        align-items: center; 
        gap: 12px;
        transition: all 0.2s;
        position: relative;
    }

    .suggestion-item:hover { 
        background: #f0f9ff;
        padding-right: 12px;
    }

    .suggestion-item.empty { 
        padding: 24px 16px; 
        color: var(--text-muted); 
        font-size: 0.85rem; 
        font-weight: 600; 
        text-align: center;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        cursor: default;
    }

    .suggestion-item.empty i {
        opacity: 0.6;
    }
    
    .s-avatar { 
        width: 36px; 
        height: 36px; 
        border-radius: 10px; 
        background: linear-gradient(135deg, #0284c7 0%, #0369a1 100%); 
        color: white; 
        display: flex; 
        align-items: center; 
        justify-content: center; 
        font-size: 0.8rem; 
        font-weight: 800;
        flex-shrink: 0;
    }

    .s-info { 
        display: flex; 
        flex-direction: column;
        gap: 4px;
        flex: 1;
    }

    .s-name { 
        font-size: 0.9rem; 
        font-weight: 700; 
        color: var(--primary); 
        display: block;
    }

    .suggestion-phone { 
        font-size: 0.75rem; 
        color: #64748b; 
        font-weight: 600;
        display: block;
    }

    .s-select-hint {
        display: flex;
        align-items: center;
        gap: 4px;
        font-size: 0.7rem;
        color: #0284c7;
        font-weight: 700;
        opacity: 0;
        transition: opacity 0.2s;
        margin-left: auto;
    }

    .suggestion-item:hover .s-select-hint {
        opacity: 1;
    }

    .suggestions-list::-webkit-scrollbar {
        width: 6px;
    }

    .suggestions-list::-webkit-scrollbar-track {
        background: transparent;
    }

    .suggestions-list::-webkit-scrollbar-thumb {
        background: #cbd5e1;
        border-radius: 3px;
    }

    .suggestions-list::-webkit-scrollbar-thumb:hover {
        background: #94a3b8;
    }

    .form-footer { display: flex; justify-content: flex-end; align-items: center; gap: 32px; padding-top: 40px; border-top: 1.5px solid #f1f5f9; }
    .btn-cancel { text-decoration: none; color: #64748b; font-size: 0.9rem; font-weight: 700; transition: color 0.2s; }
    .btn-cancel:hover { color: #ef4444; }
    
    .btn-premium { background: var(--primary); color: white; padding: 14px 28px; border-radius: 14px; font-weight: 700; font-size: 0.95rem; border: none; cursor: pointer; display: flex; align-items: center; gap: 10px; transition: all 0.2s; box-shadow: 0 4px 12px rgba(15, 23, 42, 0.1); }
    .btn-premium:hover { background: #1e293b; transform: translateY(-2px); box-shadow: 0 6px 20px rgba(15, 23, 42, 0.15); }
    
    .error-msg { font-size: 0.75rem; color: #dc2626; font-weight: 700; margin-top: 8px; display: block; padding-left: 4px; }

    /* Partial Payment Fields & Summary */
    .partial-payment-field {
        transition: all 0.3s ease;
        animation: slideDown 0.3s ease;
    }

    @keyframes slideDown {
        from {
            opacity: 0;
            transform: translateY(-10px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .partial-payment-summary {
        grid-column: span 2;
        animation: slideDown 0.3s ease;
    }

    .summary-box {
        background: linear-gradient(135deg, #f0f9ff 0%, #ecf0ff 100%);
        border: 1.5px solid #bae6fd;
        border-radius: 14px;
        padding: 20px;
        display: grid;
        grid-template-columns: 1fr auto 1fr auto 1fr;
        gap: 16px;
        align-items: center;
    }

    .summary-box.mismatch {
        background: linear-gradient(135deg, #fef2f2 0%, #fff5f5 100%);
        border-color: #fecaca;
    }

    .summary-item {
        display: flex;
        flex-direction: column;
        gap: 6px;
    }

    .summary-label {
        font-size: 0.7rem;
        font-weight: 800;
        color: #64748b;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .summary-value {
        font-size: 1.1rem;
        font-weight: 800;
        color: #0284c7;
    }

    .summary-value.paid {
        color: #059669;
    }

    .summary-value.owed {
        color: #d97706;
    }

    .summary-box.mismatch .summary-value.owed {
        color: #dc2626;
    }

    .summary-divider {
        width: 1.5px;
        height: 60px;
        background: #cbd5e1;
    }

    @media (max-width: 768px) {
        .summary-box {
            grid-template-columns: 1fr;
            gap: 12px;
        }

        .summary-divider {
            display: none;
        }

        .partial-payment-summary {
            grid-column: span 1;
        }
    }
</style>
@endpush
@endsection
