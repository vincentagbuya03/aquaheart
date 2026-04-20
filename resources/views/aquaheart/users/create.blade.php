@extends('layouts.aquaheart')

@section('title', 'Add New User')
@section('page_title', 'Add New User')
@section('page_subtitle', 'Register a new administrator or cashier to the system.')

@section('page_actions')
<a href="{{ route('aquaheart.users.index') }}" class="btn-primary" style="background: white; color: var(--primary); border: 1px solid var(--border);">
    <i data-lucide="chevron-left" size="18"></i>
    Back to Users
</a>
@endsection

@section('content')
<div class="card" style="max-width: 600px; margin: 0 auto;">
    <form action="{{ route('aquaheart.users.store') }}" method="POST">
        @csrf
        
        <div class="form-group">
            <label for="name">Full Name</label>
            <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}" required>
            @error('name')
                <div class="error-msg">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group">
            <label for="email">Email Address</label>
            <input type="email" name="email" id="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email') }}" required>
            @error('email')
                <div class="error-msg">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group">
            <label for="role">User Role</label>
            <select name="role" id="role" class="form-control @error('role') is-invalid @enderror" required>
                <option value="cashier" {{ old('role') == 'cashier' ? 'selected' : '' }}>Cashier</option>
                <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>Administrator</option>
            </select>
            @error('role')
                <div class="error-msg">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group">
            <label for="password">Password</label>
            <input type="password" name="password" id="password" class="form-control @error('password') is-invalid @enderror" required>
            @error('password')
                <div class="error-msg">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group">
            <label for="password_confirmation">Confirm Password</label>
            <input type="password" name="password_confirmation" id="password_confirmation" class="form-control" required>
        </div>

        <div style="margin-top: 32px;">
            <button type="submit" class="btn-primary w-full">
                <i data-lucide="save" size="18"></i>
                Save User
            </button>
        </div>
    </form>
</div>

@push('styles')
<style>
    .form-group { margin-bottom: 20px; }
    .form-group label { display: block; font-size: 0.85rem; font-weight: 700; color: var(--text-main); margin-bottom: 8px; }
    .form-control { width: 100%; padding: 12px 16px; border-radius: 10px; border: 1px solid var(--border); background: #f8fafc; font-family: inherit; font-size: 0.9rem; transition: var(--transition); }
    .form-control:focus { outline: none; border-color: var(--accent); background: #ffffff; box-shadow: 0 0 0 4px var(--accent-soft); }
    .form-control.is-invalid { border-color: #ef4444; background: #fff5f5; }
    .error-msg { font-size: 0.8rem; color: #ef4444; margin-top: 6px; font-weight: 600; }
    .w-full { width: 100%; justify-content: center; }
</style>
@endpush
@endsection
