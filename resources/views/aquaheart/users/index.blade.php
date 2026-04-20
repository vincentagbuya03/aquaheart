@extends('layouts.aquaheart')

@section('title', 'User Management')
@section('page_title', 'User Management')
@section('page_subtitle', 'Manage administrators and cashiers who can access the system.')

@section('page_actions')
<a href="{{ route('aquaheart.users.create') }}" class="btn-primary">
    <i data-lucide="user-plus" size="18"></i>
    Add New User
</a>
@endsection

@section('content')
<div class="card">
    <div class="table-container">
        <table class="data-table">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th>Joined</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($users as $user)
                    <tr>
                        <td class="font-bold">{{ $user->name }}</td>
                        <td>{{ $user->email }}</td>
                        <td>
                            @if($user->is_admin)
                                <span class="badge badge-blue">Administrator</span>
                            @elseif($user->is_cashier)
                                <span class="badge badge-green">Cashier</span>
                            @else
                                <span class="badge badge-gray">General User</span>
                            @endif
                        </td>
                        <td>{{ $user->created_at->format('M d, Y') }}</td>
                        <td>
                            <div style="display: flex; gap: 8px;">
                                <a href="{{ route('aquaheart.users.edit', $user->id) }}" class="icon-btn" title="Edit User">
                                    <i data-lucide="edit-3" size="16"></i>
                                </a>
                                @if($user->id !== auth()->id())
                                    <form action="{{ route('aquaheart.users.destroy', $user->id) }}" method="POST" data-ajax-delete data-delete-label="user {{ $user->name }}">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="icon-btn" style="color: #ef4444; border-color: #fee2e2;" title="Delete User">
                                            <i data-lucide="trash-2" size="16"></i>
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

@push('styles')
<style>
    .alert { padding: 12px 16px; border-radius: 8px; margin-bottom: 24px; font-size: 0.9rem; font-weight: 600; }
    .alert-success { background: #f0fdf4; color: #166534; border: 1px solid #bbf7d0; }
    .alert-danger { background: #fef2f2; color: #991b1b; border: 1px solid #fecaca; }

    .table-container { overflow-x: auto; }
    .data-table { width: 100%; border-collapse: collapse; }
    .data-table th { text-align: left; padding: 16px; font-size: 0.75rem; color: var(--text-muted); text-transform: uppercase; border-bottom: 1px solid var(--border); }
    .data-table td { padding: 16px; font-size: 0.85rem; border-bottom: 1px solid var(--border); }
    .font-bold { font-weight: 700; color: var(--primary); }

    .badge { padding: 4px 10px; border-radius: 6px; font-size: 0.7rem; font-weight: 700; text-transform: uppercase; }
    .badge-blue { background: #eff6ff; color: #3b82f6; }
    .badge-green { background: #f0fdf4; color: #22c55e; }
    .badge-gray { background: #f1f5f9; color: #64748b; }

    .icon-btn { display: inline-flex; align-items: center; justify-content: center; width: 32px; height: 32px; border-radius: 6px; background: #f8fafc; color: #64748b; border: 1px solid #e2e8f0; transition: all 0.2s; cursor: pointer; text-decoration: none; }
    .icon-btn:hover { background: #eff6ff; color: #3b82f6; border-color: #bfdbfe; }
</style>
@endpush
@endsection
