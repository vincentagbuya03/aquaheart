@extends('layouts.aquaheart')

@section('title', 'Edit User Info')
@section('page_title', 'Edit User Info')
@section('page_subtitle', 'Update details for ' . $user->name . '.')

@section('page_actions')
<a href="{{ route('aquaheart.users.index') }}" class="btn-primary" style="background: white; color: var(--primary); border: 1px solid var(--border);">
    <i data-lucide="chevron-left" size="18"></i>
    Back to Users
</a>
@endsection

@section('content')
<div class="card" style="max-width: 600px; margin: 0 auto;">
    <form action="{{ route('aquaheart.users.update', $user->id) }}" method="POST" id="editUserForm">
        @csrf
        @method('PUT')
        
        <div class="form-group">
            <label for="name">Full Name</label>
            <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $user->name) }}" required>
            @error('name')
                <div class="error-msg">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group">
            <label for="email">Email Address</label>
            <input type="email" name="email" id="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email', $user->email) }}" required>
            @error('email')
                <div class="error-msg">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group">
            <label for="role">User Role</label>
            <select name="role" id="role" class="form-control @error('role') is-invalid @enderror" required>
                <option value="">-- Select Role --</option>
                <option value="cashier" {{ (old('role', $user->is_cashier ? 'cashier' : ($user->is_admin ? 'admin' : ''))) == 'cashier' ? 'selected' : '' }}>Cashier</option>
                <option value="admin" {{ (old('role', $user->is_admin ? 'admin' : ($user->is_cashier ? 'cashier' : ''))) == 'admin' ? 'selected' : '' }}>Administrator</option>
            </select>
            @error('role')
                <div class="error-msg">{{ $message }}</div>
            @enderror
        </div>

        <div class="password-section" style="padding: 20px; background: linear-gradient(135deg, #eff6ff 0%, #f0f9ff 100%); border-radius: 12px; border: 1px solid #bfdbfe; margin-top: 28px;">
            <div style="display: flex; align-items: center; gap: 8px; margin-bottom: 16px;">
                <i data-lucide="key" size="18" style="color: #3b82f6;"></i>
                <h4 style="margin: 0; font-size: 0.95rem; font-weight: 700; color: var(--primary);">Reset Password (Optional)</h4>
            </div>
            <p style="font-size: 0.8rem; color: var(--text-muted); margin: 0 0 12px 0;">Leave blank to keep the current password. Fill this only if the user forgot their password.</p>
            
            <div class="form-group">
                <label for="password" style="margin-bottom: 8px;">New Password</label>
                <input type="password" name="password" id="password" class="form-control @error('password') is-invalid @enderror" placeholder="Minimum 8 characters (leave blank to skip)">
                @error('password')
                    <div class="error-msg">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="password_confirmation">Confirm Password</label>
                <input type="password" name="password_confirmation" id="password_confirmation" class="form-control" placeholder="Confirm the new password">
            </div>
        </div>

        <div style="margin-top: 32px;">
            <button type="submit" class="btn-primary btn-submit">
                <i data-lucide="check"></i>
                Update User
            </button>
        </div>
    </form>
</div>

@push('styles')
<style>
    .form-group { 
        margin-bottom: 20px; 
    }
    
    .form-group label { 
        display: block; 
        font-size: 0.9rem; 
        font-weight: 700; 
        color: var(--text-main); 
        margin-bottom: 8px; 
    }
    
    .form-control { 
        width: 100%; 
        padding: 12px 16px; 
        border-radius: 10px; 
        border: 1px solid var(--border); 
        background: #ffffff; 
        font-family: inherit; 
        font-size: 0.9rem; 
        transition: var(--transition); 
        box-sizing: border-box;
    }
    
    .form-control:focus { 
        outline: none; 
        border-color: var(--accent); 
        background: #ffffff; 
        box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1); 
    }
    
    .form-control.is-invalid { 
        border-color: #ef4444; 
        background: #fff5f5; 
    }
    
    .error-msg { 
        font-size: 0.8rem; 
        color: #ef4444; 
        margin-top: 6px; 
        font-weight: 600; 
    }
    
    .btn-primary {
        background: var(--primary);
        color: white;
        padding: 12px 24px;
        border-radius: 10px;
        border: none;
        font-weight: 700;
        font-size: 0.9rem;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        transition: var(--transition);
    }
    
    .btn-primary:hover {
        opacity: 0.9;
        transform: translateY(-1px);
    }
    
    .btn-submit {
        width: 100%;
        justify-content: center;
    }

    .alert {
        padding: 12px 16px;
        border-radius: 8px;
        display: flex;
        align-items: center;
        gap: 12px;
        font-size: 0.9rem;
    }

    .alert-success {
        background: #f0fdf4;
        color: #166534;
        border: 1px solid #bbf7d0;
    }
</style>
@endpush

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const passwordField = document.getElementById('password');
        const confirmField = document.getElementById('password_confirmation');
        const form = document.getElementById('editUserForm');

        if (passwordField && confirmField) {
            // Clear confirmation field when password is empty
            passwordField.addEventListener('input', function() {
                if (this.value === '') {
                    confirmField.value = '';
                }
            });

            // Before form submission, clear both fields if password is empty
            form.addEventListener('submit', function(e) {
                if (passwordField.value === '') {
                    passwordField.value = '';
                    confirmField.value = '';
                }
            });
        }
    });
</script>
@endpush

@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const passwordField = document.getElementById('password');
        const confirmField = document.getElementById('password_confirmation');
        const form = document.querySelector('form');

        // Clear confirmation field when password is empty
        if (passwordField) {
            passwordField.addEventListener('input', function() {
                if (this.value === '') {
                    confirmField.value = '';
                }
            });

            // Before form submission, clear both fields if password is empty
            form.addEventListener('submit', function(e) {
                if (passwordField.value === '') {
                    passwordField.value = '';
                    confirmField.value = '';
                }
            });
        }
    });
</script>
@endpush
