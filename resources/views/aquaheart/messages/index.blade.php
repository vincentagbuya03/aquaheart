@extends('layouts.aquaheart')

@section('title', 'Contact Messages')

@section('content')
<div class="messages-header">
    <div>
        <div class="messages-kicker">ADMIN PORTAL • INBOX</div>
        <h1 class="messages-title">Contact<span>Messages</span></h1>
        <p class="messages-subtitle">Review messages submitted from the public contact page.</p>
    </div>
</div>

<div class="messages-stats-grid">
    <div class="messages-stat-card">
        <span class="messages-stat-label">UNREAD</span>
        <span class="messages-stat-value">{{ number_format($stats['unread'] ?? 0) }}</span>
    </div>
    <div class="messages-stat-card">
        <span class="messages-stat-label">TODAY</span>
        <span class="messages-stat-value">{{ number_format($stats['today'] ?? 0) }}</span>
    </div>
    <div class="messages-stat-card accent">
        <span class="messages-stat-label">TOTAL</span>
        <span class="messages-stat-value">{{ number_format($stats['total'] ?? 0) }}</span>
    </div>
</div>

<div class="card messages-container">
    <form method="GET" action="{{ route('aquaheart.messages.index') }}" class="messages-toolbar">
        <div class="messages-search">
            <i data-lucide="search" size="16"></i>
            <input type="text" name="q" value="{{ $search }}" placeholder="Search name, email, phone, inquiry, or message...">
        </div>
        <div class="messages-filter-group">
            <label for="statusFilter">Status</label>
            <select id="statusFilter" name="status" class="messages-select">
                <option value="all" {{ $status === 'all' ? 'selected' : '' }}>All</option>
                <option value="unread" {{ $status === 'unread' ? 'selected' : '' }}>Unread</option>
                <option value="read" {{ $status === 'read' ? 'selected' : '' }}>Read</option>
            </select>
        </div>
        <button type="submit" class="btn-primary" style="height: 42px;">
            <i data-lucide="filter" size="16"></i>
            Apply
        </button>
        <a href="{{ route('aquaheart.messages.index') }}" class="btn-reset">Reset</a>
    </form>

    <div class="table-responsive">
        <table class="messages-table">
            <thead>
                <tr>
                    <th>STATUS</th>
                    <th>SENDER</th>
                    <th>INQUIRY</th>
                    <th>MESSAGE</th>
                    <th>RECEIVED</th>
                    <th>ACTION</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($messages as $message)
                <tr>
                    <td>
                        <span class="status-pill {{ $message->is_read ? 'read' : 'unread' }}">
                            {{ $message->is_read ? 'READ' : 'UNREAD' }}
                        </span>
                    </td>
                    <td>
                        <div class="sender-name">{{ $message->full_name }}</div>
                        <div class="sender-email">{{ $message->email }}</div>
                        <div class="sender-phone">{{ $message->phone ?? '-' }}</div>
                    </td>
                    <td class="inquiry">{{ $message->inquiry }}</td>
                    <td class="message-preview">{{ \Illuminate\Support\Str::limit($message->message, 120) }}</td>
                    <td class="received">
                        <div>{{ optional($message->created_at)->format('M d, Y') }}</div>
                        <small>{{ optional($message->created_at)->format('h:i A') }}</small>
                    </td>
                    <td>
                        @if(!$message->is_read)
                            <form method="POST" action="{{ route('aquaheart.messages.read', $message) }}">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="btn-mark-read">Mark Read</button>
                            </form>
                        @else
                            <span class="already-read">Done</span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="messages-empty">No contact messages found for the selected filters.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if ($messages->hasPages())
    <div class="messages-pagination">
        {{ $messages->links('pagination::simple-bootstrap-5') }}
    </div>
    @endif
</div>

@push('styles')
<style>
    .section-header { display: none !important; }

    .messages-header { margin-bottom: 24px; }
    .messages-kicker { font-size: 0.68rem; font-weight: 800; color: #0284c7; text-transform: uppercase; letter-spacing: 1.1px; margin-bottom: 8px; }
    .messages-title { font-size: 2rem; font-weight: 800; letter-spacing: -0.8px; color: var(--primary); }
    .messages-title span { color: #0284c7; }
    .messages-subtitle { margin-top: 6px; color: var(--text-muted); font-size: 0.92rem; }

    .messages-stats-grid { display: grid; grid-template-columns: repeat(3, minmax(0, 1fr)); gap: 12px; margin-bottom: 20px; }
    .messages-stat-card { background: #ffffff; border: 1px solid var(--border); border-radius: 14px; padding: 14px 16px; display: flex; flex-direction: column; gap: 6px; }
    .messages-stat-card.accent { background: #f0f9ff; border-color: #bae6fd; }
    .messages-stat-label { font-size: 0.66rem; color: var(--text-muted); font-weight: 800; letter-spacing: 0.8px; }
    .messages-stat-value { font-size: 1.35rem; color: var(--primary); font-weight: 800; line-height: 1; }

    .messages-container { padding: 0; border-radius: 18px; overflow: hidden; }
    .messages-toolbar { padding: 18px 20px; border-bottom: 1px solid var(--border); display: flex; gap: 10px; align-items: center; flex-wrap: wrap; }
    .messages-search { display: flex; align-items: center; gap: 9px; background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 10px; padding: 10px 12px; min-width: 320px; flex: 1; }
    .messages-search i { color: #94a3b8; }
    .messages-search input { border: 0; outline: none; font: inherit; background: transparent; width: 100%; }

    .messages-filter-group { display: flex; align-items: center; gap: 8px; }
    .messages-filter-group label { font-size: 0.8rem; color: var(--text-muted); font-weight: 700; }
    .messages-select { height: 42px; min-width: 140px; border: 1px solid #e2e8f0; border-radius: 10px; padding: 0 12px; font: inherit; color: var(--primary); background: #fff; }
    .btn-reset { display: inline-flex; align-items: center; justify-content: center; height: 42px; padding: 0 14px; border: 1px solid #e2e8f0; border-radius: 10px; color: var(--text-main); text-decoration: none; font-weight: 700; }

    .messages-table { width: 100%; border-collapse: collapse; }
    .messages-table th { padding: 14px 20px; text-align: left; font-size: 0.64rem; text-transform: uppercase; color: var(--text-muted); font-weight: 800; letter-spacing: 1px; background: #fcfdff; border-bottom: 1px solid var(--border); }
    .messages-table td { padding: 14px 20px; border-bottom: 1px solid #f1f5f9; font-size: 0.87rem; color: var(--text-main); vertical-align: top; }

    .status-pill { display: inline-flex; border-radius: 999px; padding: 4px 10px; font-size: 0.68rem; font-weight: 800; letter-spacing: 0.3px; }
    .status-pill.unread { background: #dbeafe; color: #1d4ed8; }
    .status-pill.read { background: #dcfce7; color: #166534; }

    .sender-name { font-weight: 700; margin-bottom: 4px; }
    .sender-email { font-size: 0.78rem; color: #475569; }
    .sender-phone { font-size: 0.78rem; color: #334155; margin-top: 2px; }
    .inquiry { font-weight: 600; }
    .message-preview { max-width: 420px; color: #334155; }
    .received { white-space: nowrap; }
    .received small { display: block; margin-top: 4px; color: #64748b; }

    .btn-mark-read { border: 1px solid #bfdbfe; background: #eff6ff; color: #1d4ed8; border-radius: 8px; padding: 7px 11px; font-size: 0.76rem; font-weight: 800; cursor: pointer; }
    .already-read { font-size: 0.78rem; color: #166534; font-weight: 700; }

    .messages-empty { text-align: center; padding: 32px 20px; color: var(--text-muted); font-weight: 600; }
    .messages-pagination { padding: 14px 20px; border-top: 1px solid var(--border); display: flex; justify-content: flex-end; }

    @media (max-width: 1100px) {
        .messages-stats-grid { grid-template-columns: 1fr; }
        .messages-search { min-width: 0; }
    }
</style>
@endpush
@endsection
