@extends('layouts.aquaheart')

@section('title', 'Cashier Settings')
@section('page_title', 'Settings')
@section('page_subtitle', 'Manage your profile and account preferences')

@section('content')

@if (session('success'))
    <div class="alert alert-success">
        <i data-lucide="check-circle"></i>
        <span>{{ session('success') }}</span>
    </div>
@endif

@if ($errors->any())
    <div class="alert alert-error">
        <i data-lucide="alert-circle"></i>
        <div>
            <strong>Validation errors:</strong>
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    </div>
@endif

<div class="settings-grid">
    <!-- Profile Settings Card -->
    <div class="card settings-card">
        <div class="card-header">
            <h3><i data-lucide="user"></i> Profile Information</h3>
            <p>Update your personal details</p>
        </div>

        <form method="POST" action="{{ route('aquaheart.cashier.settings.updateProfile') }}" class="settings-form">
            @csrf
            @method('PUT')

            <div class="form-group">
                <label for="name">Full Name</label>
                <input 
                    type="text" 
                    id="name" 
                    name="name" 
                    value="{{ old('name', $user->name) }}"
                    class="form-input @error('name') error @enderror"
                    required
                >
                @error('name')
                    <span class="error-text">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="email">Email Address</label>
                <input 
                    type="email" 
                    id="email" 
                    name="email" 
                    value="{{ old('email', $user->email) }}"
                    class="form-input @error('email') error @enderror"
                    required
                >
                @error('email')
                    <span class="error-text">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label>Cashier Status</label>
                <div class="status-badge {{ $user->is_cashier ? 'active' : 'inactive' }}">
                    <i data-lucide="{{ $user->is_cashier ? 'check-circle' : 'x-circle' }}"></i>
                    <span>{{ $user->is_cashier ? 'Active Cashier' : 'Not Assigned' }}</span>
                </div>
            </div>

            <button type="submit" class="btn-primary full-width">
                <i data-lucide="save"></i>
                Save Changes
            </button>
        </form>
    </div>

    <!-- Password Settings Card -->
    <div class="card settings-card">
        <div class="card-header">
            <h3><i data-lucide="lock"></i> Change Password</h3>
            <p>Update your account security</p>
        </div>

        <form method="POST" action="{{ route('aquaheart.cashier.settings.updatePassword') }}" class="settings-form">
            @csrf
            @method('PUT')

            <div class="form-group">
                <label for="current_password">Current Password</label>
                <input 
                    type="password" 
                    id="current_password" 
                    name="current_password"
                    class="form-input @error('current_password') error @enderror"
                    required
                >
                @error('current_password')
                    <span class="error-text">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="password">New Password</label>
                <input 
                    type="password" 
                    id="password" 
                    name="password"
                    class="form-input @error('password') error @enderror"
                    placeholder="At least 8 characters"
                    required
                >
                @error('password')
                    <span class="error-text">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="password_confirmation">Confirm New Password</label>
                <input 
                    type="password" 
                    id="password_confirmation" 
                    name="password_confirmation"
                    class="form-input"
                    required
                >
            </div>

            <button type="submit" class="btn-primary full-width">
                <i data-lucide="key"></i>
                Update Password
            </button>
        </form>
    </div>

    <!-- Account Info Card -->
    <div class="card settings-card">
        <div class="card-header">
            <h3><i data-lucide="info"></i> Account Information</h3>
            <p>View your account details</p>
        </div>

        <div class="info-list">
            <div class="info-item">
                <span class="info-label">Account ID</span>
                <span class="info-value">{{ $user->id }}</span>
            </div>
            <div class="info-item">
                <span class="info-label">Account Type</span>
                <span class="info-value">
                    <span class="badge badge-primary">
                        {{ $user->is_admin ? 'Administrator' : 'Cashier' }}
                    </span>
                </span>
            </div>
            <div class="info-item">
                <span class="info-label">Member Since</span>
                <span class="info-value">{{ $user->created_at->format('M d, Y') }}</span>
            </div>
            <div class="info-item">
                <span class="info-label">Last Updated</span>
                <span class="info-value">{{ $user->updated_at->format('M d, Y h:i A') }}</span>
            </div>
        </div>
    </div>

    <!-- Security Tips Card -->
    <div class="card settings-card" style="grid-column: span 2;">
        <div class="card-header">
            <h3><i data-lucide="shield"></i> Security Tips</h3>
            <p>Keep your account safe</p>
        </div>

        <ul class="tips-list">
            <li>
                <span class="tip-icon">🔒</span>
                <span>Use a strong password with a mix of letters, numbers, and symbols</span>
            </li>
            <li>
                <span class="tip-icon">🚫</span>
                <span>Never share your password with anyone</span>
            </li>
            <li>
                <span class="tip-icon">⏰</span>
                <span>Change your password regularly for better security</span>
            </li>
            <li>
                <span class="tip-icon">📧</span>
                <span>Keep your email address up to date for account recovery</span>
            </li>
        </ul>
    </div>
</div>

@push('styles')
<style>
    .settings-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 24px;
        margin-bottom: 40px;
    }

    .settings-card {
        display: flex;
        flex-direction: column;
    }

    .card-header {
        margin-bottom: 28px;
        padding-bottom: 16px;
        border-bottom: 1px solid var(--border);
    }

    .card-header h3 {
        display: flex;
        align-items: center;
        gap: 12px;
        font-size: 1.1rem;
        font-weight: 800;
        color: var(--primary);
        margin-bottom: 4px;
    }

    .card-header p {
        font-size: 0.85rem;
        color: var(--text-muted);
    }

    .settings-form {
        display: flex;
        flex-direction: column;
        gap: 20px;
    }

    .form-group {
        display: flex;
        flex-direction: column;
        gap: 8px;
    }

    .form-group label {
        font-size: 0.9rem;
        font-weight: 700;
        color: var(--text-main);
    }

    .form-input {
        padding: 10px 14px;
        border: 1px solid var(--border);
        border-radius: 8px;
        font-size: 0.9rem;
        color: var(--text-main);
        background: #ffffff;
        transition: var(--transition);
        font-family: inherit;
    }

    .form-input:focus {
        outline: none;
        border-color: var(--accent);
        box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.08);
    }

    .form-input.error {
        border-color: #dc2626;
    }

    .error-text {
        font-size: 0.8rem;
        color: #dc2626;
    }

    .full-width {
        width: 100%;
    }

    .status-badge {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 8px 14px;
        border-radius: 8px;
        font-size: 0.9rem;
        font-weight: 600;
        width: fit-content;
    }

    .status-badge.active {
        background: #f0fdf4;
        color: #166534;
    }

    .status-badge.inactive {
        background: #fee2e2;
        color: #991b1b;
    }

    .btn-primary {
        background: var(--primary);
        color: white;
        padding: 10px 20px;
        border-radius: 8px;
        border: none;
        font-weight: 700;
        font-size: 0.9rem;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        transition: var(--transition);
    }

    .btn-primary:hover {
        opacity: 0.9;
        transform: translateY(-1px);
    }

    .info-list {
        display: flex;
        flex-direction: column;
        gap: 16px;
    }

    .info-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 12px;
        background: var(--bg);
        border-radius: 8px;
    }

    .info-label {
        font-size: 0.9rem;
        font-weight: 700;
        color: var(--text-muted);
    }

    .info-value {
        font-size: 0.9rem;
        color: var(--text-main);
        font-weight: 600;
    }

    .badge {
        display: inline-block;
        padding: 4px 12px;
        border-radius: 6px;
        font-size: 0.75rem;
        font-weight: 700;
        text-transform: uppercase;
    }

    .badge-primary {
        background: #eff6ff;
        color: #3b82f6;
    }

    .tips-list {
        list-style: none;
        display: flex;
        flex-direction: column;
        gap: 14px;
    }

    .tips-list li {
        display: flex;
        align-items: flex-start;
        gap: 12px;
        padding: 12px;
        background: var(--bg);
        border-radius: 8px;
        border-left: 3px solid var(--accent);
    }

    .tip-icon {
        font-size: 1.2rem;
        flex-shrink: 0;
    }

    .tips-list span:last-child {
        font-size: 0.9rem;
        color: var(--text-main);
        line-height: 1.5;
    }

    .alert {
        padding: 16px;
        border-radius: 8px;
        margin-bottom: 24px;
        display: flex;
        align-items: flex-start;
        gap: 12px;
        font-size: 0.9rem;
    }

    .alert i {
        flex-shrink: 0;
        margin-top: 2px;
    }

    .alert-success {
        background: #f0fdf4;
        color: #166534;
        border: 1px solid #bbf7d0;
    }

    .alert-error {
        background: #fee2e2;
        color: #991b1b;
        border: 1px solid #fca5a5;
    }

    .alert ul {
        margin: 8px 0 0 24px;
        padding: 0;
    }

    @media (max-width: 1024px) {
        .settings-grid {
            grid-template-columns: 1fr;
        }

        .card-header h3 {
            grid-column: span 1;
        }

        .settings-card:last-child {
            grid-column: span 1;
        }
    }
</style>
@endpush

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Auto-hide success alerts after 5 seconds
        const alerts = document.querySelectorAll('.alert-success');
        alerts.forEach(alert => {
            setTimeout(() => {
                alert.style.transition = 'opacity 0.3s ease';
                alert.style.opacity = '0';
                setTimeout(() => alert.remove(), 300);
            }, 5000);
        });
    });
</script>
@endpush

@endsection
