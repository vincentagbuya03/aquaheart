@extends('layouts.aquaheart')

@section('title', isset($customer) ? 'Edit Customer' : 'Add Customer')
@section('page_title', isset($customer) ? 'Edit Profile' : 'New Customer Integration')
@section('page_subtitle', 'Register a new household or update existing contact details.')

@section('content')
<div class="form-container-boxed">
    <div class="card form-card">
        <form method="POST" action="{{ isset($customer) ? route('aquaheart.customers.update', $customer) : route('aquaheart.customers.store') }}">
            @csrf
            @if (isset($customer))
                @method('PUT')
            @endif

            <div class="form-grid">
                <div class="form-group full">
                    <label class="field-label">Full Name / Household Name</label>
                    <input 
                        type="text" 
                        name="name" 
                        class="form-input @error('name') is-invalid @enderror"
                        value="{{ old('name', $customer->name ?? '') }}"
                        required
                        placeholder="e.g. John Doe or Smith Family"
                    >
                    @error('name') <span class="error-msg">{{ $message }}</span> @enderror
                </div>

                <div class="form-group full">
                    <label class="field-label">Primary Contact Number</label>
                    <input 
                        type="text" 
                        name="phone" 
                        class="form-input @error('phone') is-invalid @enderror"
                        value="{{ old('phone', $customer->phone ?? '') }}"
                        placeholder="+63 9xx xxx xxxx"
                    >
                    @error('phone') <span class="error-msg">{{ $message }}</span> @enderror
                </div>

                <div class="form-group full">
                    <label class="field-label">Street Address</label>
                    <textarea 
                        name="street" 
                        class="form-input @error('street') is-invalid @enderror"
                        placeholder="House No, Street, Barangay..."
                        rows="4"
                    >{{ old('street', $customer->street ?? '') }}</textarea>
                    @error('street') <span class="error-msg">{{ $message }}</span> @enderror
                </div>

                <div class="form-group full">
                    <label class="field-label">City</label>
                    <input 
                        type="text" 
                        name="city" 
                        class="form-input @error('city') is-invalid @enderror"
                        value="{{ old('city', $customer->city ?? '') }}"
                        placeholder="City or municipality"
                    >
                    @error('city') <span class="error-msg">{{ $message }}</span> @enderror
                </div>

                <div class="form-group full">
                    <label class="field-label">Province</label>
                    <input 
                        type="text" 
                        name="province" 
                        class="form-input @error('province') is-invalid @enderror"
                        value="{{ old('province', $customer->province ?? '') }}"
                        placeholder="Province or region"
                    >
                    @error('province') <span class="error-msg">{{ $message }}</span> @enderror
                </div>

                <div class="form-group full">
                    <label class="field-label">ZIP Code</label>
                    <input 
                        type="text" 
                        name="zip_code" 
                        class="form-input @error('zip_code') is-invalid @enderror"
                        value="{{ old('zip_code', $customer->zip_code ?? '') }}"
                        placeholder="Postal code"
                    >
                    @error('zip_code') <span class="error-msg">{{ $message }}</span> @enderror
                </div>
            </div>

            <div class="form-footer">
                <a href="{{ route('aquaheart.customers.index') }}" class="btn-cancel">Discard changes</a>
                <button type="submit" class="btn-primary">
                    <i data-lucide="check" size="18"></i>
                    {{ isset($customer) ? 'Save Profile' : 'Create Account' }}
                </button>
            </div>
        </form>
    </div>
</div>

@push('styles')
<style>
    .form-container-boxed { max-width: 600px; margin: 0 auto; }
    .form-card { padding: 40px; border-radius: 20px; }

    .form-grid { display: flex; flex-direction: column; gap: 24px; margin-bottom: 40px; }
    .field-label { display: block; font-size: 0.8rem; font-weight: 800; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 10px; }
    
    .form-input { 
        width: 100%; 
        padding: 12px 16px; 
        border: 1px solid var(--border); 
        border-radius: 12px; 
        font-family: inherit; 
        font-size: 0.9rem; 
        background: var(--bg); 
        color: var(--primary);
        transition: var(--transition);
        resize: vertical;
    }
    .form-input:focus { outline: none; border-color: var(--accent); background: white; box-shadow: 0 0 0 4px var(--accent-soft); }

    .form-footer { display: flex; justify-content: flex-end; align-items: center; gap: 24px; padding-top: 30px; border-top: 1px solid var(--border); }
    .btn-cancel { text-decoration: none; color: var(--text-muted); font-size: 0.9rem; font-weight: 700; transition: var(--transition); }
    .btn-cancel:hover { color: var(--danger); }

    .error-msg { font-size: 0.75rem; color: var(--danger); font-weight: 700; margin-top: 6px; display: block; }
</style>
@endpush
@endsection
