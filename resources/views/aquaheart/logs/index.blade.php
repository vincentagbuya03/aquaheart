@extends('layouts.aquaheart')

@section('title', 'Activity Logs')

@section('content')
<div class="logs-header">
    <div>
        <div class="logs-kicker">ADMIN PORTAL &bull; SECURITY TRAIL</div>
        <h1 class="logs-title">Activity<span>Logs</span></h1>
        <p class="logs-subtitle">Track create, update, and delete actions across AquaHeart modules.</p>
    </div>
</div>

<div class="logs-stats-grid">
    <div class="logs-stat-card">
        <span class="logs-stat-label">TODAY</span>
        <span class="logs-stat-value">{{ number_format($stats['today'] ?? 0) }}</span>
    </div>
    <div class="logs-stat-card">
        <span class="logs-stat-label">CREATED</span>
        <span class="logs-stat-value">{{ number_format($stats['created'] ?? 0) }}</span>
    </div>
    <div class="logs-stat-card">
        <span class="logs-stat-label">UPDATED</span>
        <span class="logs-stat-value">{{ number_format($stats['updated'] ?? 0) }}</span>
    </div>
    <div class="logs-stat-card">
        <span class="logs-stat-label">DELETED</span>
        <span class="logs-stat-value">{{ number_format($stats['deleted'] ?? 0) }}</span>
    </div>
    <div class="logs-stat-card accent">
        <span class="logs-stat-label">TOTAL</span>
        <span class="logs-stat-value">{{ number_format($stats['total'] ?? 0) }}</span>
    </div>
</div>

<div class="card logs-container">
    <form method="GET" action="{{ route('aquaheart.logs.index') }}" class="logs-toolbar">
        <div class="logs-search">
            <i data-lucide="search" size="16"></i>
            <input type="text" name="q" value="{{ $search }}" placeholder="Search user, route, or record id...">
        </div>
        <div class="logs-filter-group">
            <label for="actionFilter">Action</label>
            <select id="actionFilter" name="action" class="logs-select">
                <option value="all" {{ $actionFilter === 'all' ? 'selected' : '' }}>All</option>
                <option value="created" {{ $actionFilter === 'created' ? 'selected' : '' }}>Created</option>
                <option value="updated" {{ $actionFilter === 'updated' ? 'selected' : '' }}>Updated</option>
                <option value="deleted" {{ $actionFilter === 'deleted' ? 'selected' : '' }}>Deleted</option>
                <option value="status_changed" {{ $actionFilter === 'status_changed' ? 'selected' : '' }}>Status Changed</option>
            </select>
        </div>
        <button type="submit" class="btn-primary" style="height: 42px;">
            <i data-lucide="filter" size="16"></i>
            Apply
        </button>
        <a href="{{ route('aquaheart.logs.index') }}" class="btn-reset">Reset</a>
    </form>

    <div class="table-responsive">
        <table class="logs-table">
            <thead>
                <tr>
                    <th>WHEN</th>
                    <th>USER</th>
                    <th>ACTION</th>
                    <th>TARGET</th>
                    <th>ROUTE</th>
                    <th>DETAILS</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($logs as $log)
                @php
                    $actionClass = match ($log->action) {
                        'created' => 'created',
                        'updated', 'status_changed' => 'updated',
                        'deleted' => 'deleted',
                        default => 'neutral',
                    };
                @endphp
                <tr>
                    <td class="logs-time">
                        <div>{{ optional($log->created_at)->format('M d, Y') }}</div>
                        <small>{{ optional($log->created_at)->format('h:i A') }}</small>
                    </td>
                    <td>
                        <div class="logs-user">
                            <div class="logs-avatar">{{ strtoupper(substr($log->user->name ?? 'S', 0, 1)) }}</div>
                            <span>{{ $log->user->name ?? 'System' }}</span>
                        </div>
                    </td>
                    <td>
                        <span class="action-pill {{ $actionClass }}">{{ strtoupper(str_replace('_', ' ', $log->action)) }}</span>
                    </td>
                    <td>
                        <span class="logs-target">{{ $log->entity_type ?? 'Record' }}</span>
                        @if($log->entity_id)
                            <small>#{{ \Illuminate\Support\Str::limit($log->entity_id, 14, '') }}</small>
                        @endif
                    </td>
                    <td class="logs-route">{{ $log->route_name ?? '-' }}</td>
                    <td class="logs-description">{{ $log->description ?? '-' }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="logs-empty">No activity logs found for the current filters.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if ($logs->hasPages())
    <div class="logs-pagination">
        {{ $logs->links('pagination::simple-bootstrap-5') }}
    </div>
    @endif
</div>

@push('styles')
<style>
    .section-header { display: none !important; }

    .logs-header { margin-bottom: 24px; }
    .logs-kicker { font-size: 0.68rem; font-weight: 800; color: #0284c7; text-transform: uppercase; letter-spacing: 1.1px; margin-bottom: 8px; }
    .logs-title { font-size: 2rem; font-weight: 800; letter-spacing: -0.8px; color: var(--primary); }
    .logs-title span { color: #0284c7; }
    .logs-subtitle { margin-top: 6px; color: var(--text-muted); font-size: 0.92rem; }

    .logs-stats-grid { display: grid; grid-template-columns: repeat(5, minmax(0, 1fr)); gap: 12px; margin-bottom: 20px; }
    .logs-stat-card { background: #ffffff; border: 1px solid var(--border); border-radius: 14px; padding: 14px 16px; display: flex; flex-direction: column; gap: 6px; }
    .logs-stat-card.accent { background: #f0f9ff; border-color: #bae6fd; }
    .logs-stat-label { font-size: 0.66rem; color: var(--text-muted); font-weight: 800; letter-spacing: 0.8px; }
    .logs-stat-value { font-size: 1.35rem; color: var(--primary); font-weight: 800; line-height: 1; }

    .logs-container { padding: 0; border-radius: 18px; overflow: hidden; }
    .logs-toolbar { padding: 18px 20px; border-bottom: 1px solid var(--border); display: flex; gap: 10px; align-items: center; flex-wrap: wrap; }
    .logs-search { display: flex; align-items: center; gap: 9px; background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 10px; padding: 10px 12px; min-width: 320px; flex: 1; }
    .logs-search i { color: #94a3b8; }
    .logs-search input { border: 0; outline: none; font: inherit; background: transparent; width: 100%; }

    .logs-filter-group { display: flex; align-items: center; gap: 8px; }
    .logs-filter-group label { font-size: 0.8rem; color: var(--text-muted); font-weight: 700; }
    .logs-select { height: 42px; min-width: 150px; border: 1px solid #e2e8f0; border-radius: 10px; padding: 0 12px; font: inherit; color: var(--primary); background: #fff; }
    .btn-reset { display: inline-flex; align-items: center; justify-content: center; height: 42px; padding: 0 14px; border: 1px solid #e2e8f0; border-radius: 10px; color: var(--text-main); text-decoration: none; font-weight: 700; }

    .logs-table { width: 100%; border-collapse: collapse; }
    .logs-table th { padding: 14px 20px; text-align: left; font-size: 0.64rem; text-transform: uppercase; color: var(--text-muted); font-weight: 800; letter-spacing: 1px; background: #fcfdff; border-bottom: 1px solid var(--border); }
    .logs-table td { padding: 14px 20px; border-bottom: 1px solid #f1f5f9; font-size: 0.87rem; color: var(--text-main); vertical-align: middle; }

    .logs-time { white-space: nowrap; }
    .logs-time small { display: block; color: var(--text-muted); margin-top: 4px; font-size: 0.72rem; }
    .logs-user { display: flex; align-items: center; gap: 10px; }
    .logs-avatar { width: 30px; height: 30px; border-radius: 8px; background: #e0f2fe; color: #0369a1; font-size: 0.75rem; font-weight: 800; display: flex; align-items: center; justify-content: center; }

    .action-pill { display: inline-flex; align-items: center; border-radius: 999px; padding: 4px 10px; font-size: 0.68rem; font-weight: 800; letter-spacing: 0.4px; }
    .action-pill.created { background: #dcfce7; color: #166534; }
    .action-pill.updated { background: #e0f2fe; color: #075985; }
    .action-pill.deleted { background: #fee2e2; color: #991b1b; }
    .action-pill.neutral { background: #f1f5f9; color: #334155; }

    .logs-target { font-weight: 700; display: block; }
    .logs-route { color: #475569; font-family: ui-monospace, SFMono-Regular, Menlo, Monaco, Consolas, monospace; font-size: 0.75rem; }
    .logs-description { color: #475569; }
    .logs-empty { text-align: center; padding: 32px 20px; color: var(--text-muted); font-weight: 600; }

    .logs-pagination { padding: 14px 20px; border-top: 1px solid var(--border); display: flex; justify-content: flex-end; }

    @media (max-width: 1100px) {
        .logs-stats-grid { grid-template-columns: repeat(2, minmax(0, 1fr)); }
        .logs-search { min-width: 0; }
    }
</style>
@endpush
@endsection
