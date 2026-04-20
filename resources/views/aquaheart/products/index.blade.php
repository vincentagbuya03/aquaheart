@extends('layouts.aquaheart')

@section('title', 'Inventory Monitor')

@section('content')

<div class="inventory-header">
    <div class="inventory-title-group">
        <div class="breadcrumb">ADMIN PORTAL &bull; WAREHOUSE</div>
        <h1 class="inventory-title">Inventory<span>Monitor</span></h1>
    </div>
</div>

<div class="highlights-grid">
    @if($highDemandProduct)
    <div class="highlight-card main-highlight">
        <div class="card-header-flex">
            <span class="badge badge-cyan">
                <div class="badge-dot"></div>
                HIGH DEMAND ITEM
            </span>
            <i data-lucide="droplet" class="water-icon"></i>
        </div>
        
        <h2 class="highlight-title">{{ $highDemandProduct->name }}</h2>
        <p class="highlight-desc">
            {{ $highDemandProduct->description ?? 'This is a high-demand item. Maintain adequate stock levels to meet customer demand.' }}
        </p>
        
        <div class="stats-row">
            <div class="stat-block">
                <span class="stat-label">CURRENT STOCK</span>
                <div class="stat-value">{{ number_format($highDemandProduct->stock_quantity) }} <span>Units</span></div>
            </div>
            <div class="stat-divider"></div>
            <div class="stat-block">
                <span class="stat-label">REORDER AT</span>
                <div class="stat-value">{{ number_format($highDemandProduct->reorder_level) }} <span>Units</span></div>
            </div>
        </div>
    </div>
    @endif
    
    @if($lowStockProduct)
    <div class="highlight-card alert-highlight">
        <div class="alert-top">
            <div class="alert-icon-box">
                <i data-lucide="bell"></i>
            </div>
            <span class="badge badge-red">LOW STOCK</span>
        </div>
        
        <div class="alert-content">
            <h3 class="alert-title">{{ $lowStockProduct->name }}</h3>
            <div class="alert-value">{{ number_format($lowStockProduct->stock_quantity) }}</div>
            <div class="alert-subtitle">Critical level: {{ $lowStockProduct->reorder_level }} units</div>
        </div>
        
        <a href="{{ route('aquaheart.products.edit', $lowStockProduct) }}" class="btn-black" style="text-align: center; text-decoration: none;">Restock Now</a>
    </div>
    @endif
</div>

<div class="card table-container">
    <div class="table-header-row">
        <h3 class="table-title">Detailed Asset List</h3>
        <div class="table-actions">
            <button class="icon-btn"><i data-lucide="filter" size="18"></i></button>
            <button class="icon-btn"><i data-lucide="download" size="18"></i></button>
        </div>
    </div>

    <div class="table-responsive">
        <table class="inventory-table">
            <thead>
                <tr>
                    <th>ITEM DESCRIPTION</th>
                    <th>UNIT</th>
                    <th class="text-center">CURRENT STOCK</th>
                    <th class="text-center">REORDER LEVEL</th>
                    <th>STATUS</th>
                    <th class="text-right">ACTIONS</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($products as $product)
                @php
                    $unit = 'Piece';
                    $icon = 'droplet';
                    $iconColor = '#0284c7';
                    $iconBg = '#f0f9ff';
                    
                    if (str_contains(strtolower($product->name), 'cap')) {
                        $unit = 'Box (500)';
                        $icon = 'circle-dot';
                    } elseif (str_contains(strtolower($product->name), 'seal')) {
                        $unit = 'Roll (1000)';
                        $icon = 'shield-check';
                    } elseif (str_contains(strtolower($product->name), 'filter')) {
                        $unit = 'Piece';
                        $icon = 'filter';
                    }

                    $status = 'Healthy';
                    $statusClass = 'status-healthy';
                    if ($product->stock_quantity <= $product->reorder_level) {
                        $status = 'Critical';
                        $statusClass = 'status-critical';
                    } elseif ($product->stock_quantity <= ($product->reorder_level * 1.5)) {
                        $status = 'Moderate';
                        $statusClass = 'status-moderate';
                    }
                @endphp
                <tr>
                    <td>
                        <div class="item-desc-cell">
                            <div class="item-icon" style="background: {{ $iconBg }}; color: {{ $iconColor }};">
                                <i data-lucide="{{ $icon }}" size="18"></i>
                            </div>
                            <div>
                                <div class="item-name">{{ $product->name }}</div>
                                <div class="item-subtitle">{{ \Illuminate\Support\Str::limit($product->description, 50) }}</div>
                            </div>
                        </div>
                    </td>
                    <td class="unit-cell">{{ $unit }}</td>
                    <td class="text-center stock-value">{{ number_format($product->stock_quantity) }}</td>
                    <td class="text-center">{{ number_format($product->reorder_level) }}</td>
                    <td>
                        <span class="status-pill {{ $statusClass }}">{{ $status }}</span>
                    </td>
                    <td class="text-right">
                        <div class="action-buttons">
                            @if($status === 'Critical')
                                <a href="{{ route('aquaheart.products.edit', $product) }}" class="btn-restock-sm" style="text-decoration: none;">Restock</a>
                            @else
                                <a href="{{ route('aquaheart.products.show', $product) }}" class="action-icon b-blue"><i data-lucide="plus-square" size="16"></i></a>
                            @endif
                            <a href="{{ route('aquaheart.products.edit', $product) }}" class="action-icon"><i data-lucide="pencil" size="16"></i></a>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    
    <div class="table-footer-row">
        <div class="showing-text">Showing {{ $products->count() }} of {{ $products->total() }} inventory items</div>
        <div class="pagination-simple">
            <a href="{{ $products->previousPageUrl() }}" class="page-link {{ $products->onFirstPage() ? 'disabled' : '' }}">Previous Page</a>
            <a href="{{ $products->nextPageUrl() }}" class="page-link {{ !$products->hasMorePages() ? 'disabled' : '' }}">Next Page</a>
        </div>
    </div>
</div>

@push('styles')
<style>
    /* Hide Default Header from layout */
    .section-header { display: none !important; }
    
    .inventory-header { display: flex; justify-content: space-between; align-items: flex-end; margin-bottom: 24px; }
    
    .breadcrumb { font-size: 0.7rem; font-weight: 800; color: #0284c7; text-transform: uppercase; letter-spacing: 1px; margin-bottom: 8px; }
    .inventory-title { font-size: 2.2rem; font-weight: 800; color: var(--primary); letter-spacing: -1px; display: flex; gap: 8px; align-items: center; }
    .inventory-title span { color: #0284c7; }
    
    .highlights-grid { display: grid; grid-template-columns: 2fr 1fr; gap: 24px; margin-bottom: 32px; }
    
    .highlight-card { background: white; border-radius: 24px; padding: 32px; box-shadow: 0 4px 24px rgba(0,0,0,0.03); position: relative; overflow: hidden; }
    
    .card-header-flex { display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px; }
    .badge { display: inline-flex; align-items: center; gap: 6px; padding: 6px 12px; border-radius: 20px; font-size: 0.7rem; font-weight: 800; text-transform: uppercase; letter-spacing: 0.5px; }
    .badge-cyan { background: #cffafe; color: #0891b2; }
    .badge-red { background: #fee2e2; color: #ef4444; }
    .badge-dot { width: 6px; height: 6px; border-radius: 50%; background: currentColor; }
    
    .water-icon { color: #cbd5e1; }
    
    .highlight-title { font-size: 1.8rem; font-weight: 800; color: var(--primary); margin-bottom: 12px; letter-spacing: -0.5px; }
    .highlight-desc { color: var(--text-muted); font-size: 0.95rem; line-height: 1.6; max-width: 600px; margin-bottom: 32px; }
    
    .stats-row { display: flex; align-items: center; gap: 40px; }
    .stat-label { display: block; font-size: 0.7rem; font-weight: 800; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 8px; }
    .stat-value { font-size: 2rem; font-weight: 800; color: #0284c7; display: flex; align-items: baseline; gap: 6px; }
    .stat-value span { font-size: 1rem; color: var(--text-muted); font-weight: 600; }
    .stat-divider { width: 1px; height: 40px; background: var(--border); }
    .stat-block:nth-child(3) .stat-value { color: var(--primary); }
    
    .alert-highlight { background: #f8fafc; border: 1px solid #f1f5f9; display: flex; flex-direction: column; justify-content: space-between; }
    .alert-top { display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 20px; }
    .alert-icon-box { width: 44px; height: 44px; background: white; border-radius: 12px; display: flex; align-items: center; justify-content: center; color: #0284c7; box-shadow: 0 2px 8px rgba(0,0,0,0.05); }
    
    .alert-title { font-size: 1.1rem; font-weight: 700; color: var(--primary); margin-bottom: 8px; }
    .alert-value { font-size: 2.2rem; font-weight: 800; color: var(--primary); margin-bottom: 4px; }
    .alert-subtitle { font-size: 0.8rem; color: var(--text-muted); }
    
    .btn-black { background: var(--primary); color: white; padding: 12px 24px; border-radius: 12px; font-weight: 700; font-size: 0.9rem; border: none; cursor: pointer; width: 100%; margin-top: 24px; transition: all 0.2s; }
    .btn-black:hover { background: #1e293b; transform: translateY(-1px); }
    
    .table-container { padding: 0; border-radius: 20px; box-shadow: 0 4px 20px rgba(0,0,0,0.03); margin-bottom: 32px; }
    .table-header-row { display: flex; justify-content: space-between; align-items: center; padding: 24px 32px; border-bottom: 1px solid var(--border); }
    .table-title { font-size: 1.2rem; font-weight: 800; color: var(--primary); }
    .table-actions { display: flex; gap: 12px; }
    .icon-btn { width: 36px; height: 36px; border-radius: 10px; background: white; border: 1px solid var(--border); display: flex; align-items: center; justify-content: center; color: var(--text-muted); cursor: pointer; transition: all 0.2s; }
    .icon-btn:hover { background: #f8fafc; color: var(--primary); }
    
    .table-responsive { width: 100%; overflow-x: auto; }
    .inventory-table { width: 100%; border-collapse: collapse; min-width: 800px; }
    .inventory-table th { padding: 16px 32px; text-align: left; font-size: 0.65rem; font-weight: 800; color: var(--text-muted); text-transform: uppercase; letter-spacing: 1px; border-bottom: 1px solid var(--border); }
    .inventory-table td { padding: 20px 32px; border-bottom: 1px solid var(--border); vertical-align: middle; }
    
    .item-desc-cell { display: flex; align-items: center; gap: 16px; }
    .item-icon { width: 40px; height: 40px; border-radius: 12px; display: flex; align-items: center; justify-content: center; flex-shrink: 0; }
    .item-name { font-weight: 700; color: var(--primary); margin-bottom: 4px; font-size: 0.95rem; }
    .item-subtitle { font-size: 0.75rem; color: var(--text-muted); line-height: 1.4; max-width: 200px; }
    
    .unit-cell { color: var(--text-muted); font-size: 0.85rem; }
    .stock-value { font-weight: 800; font-size: 1rem; color: var(--primary); }
    
    .text-center { text-align: center !important; }
    .text-right { text-align: right !important; }
    
    .status-pill { display: inline-flex; padding: 6px 16px; border-radius: 20px; font-size: 0.7rem; font-weight: 800; }
    .status-healthy { background: #ecfdf5; color: #059669; }
    .status-critical { background: #fee2e2; color: #ef4444; }
    .status-moderate { background: #e0f2fe; color: #0284c7; }
    
    .action-buttons { display: flex; align-items: center; justify-content: flex-end; gap: 12px; }
    .btn-restock-sm { background: #0284c7; color: white; border: none; padding: 8px 16px; border-radius: 8px; font-size: 0.75rem; font-weight: 700; cursor: pointer; }
    .action-icon { color: var(--text-muted); transition: color 0.2s; }
    .action-icon:hover { color: var(--primary); }
    .action-icon.b-blue { color: #0284c7; }
    
    .table-footer-row { display: flex; justify-content: space-between; align-items: center; padding: 20px 32px; }
    .showing-text { font-size: 0.8rem; color: var(--text-muted); }
    .pagination-simple { display: flex; gap: 16px; }
    .page-link { font-size: 0.8rem; color: var(--text-muted); text-decoration: none; font-weight: 600; }
    .page-link:hover { color: var(--primary); }
    .page-link.disabled { opacity: 0.5; pointer-events: none; }
    
    @media (max-width: 1024px) {
        .highlights-grid { grid-template-columns: 1fr; }
        .stats-row { flex-direction: column; align-items: flex-start; gap: 20px; }
        .stat-divider { width: 100%; height: 1px; }
    }
</style>
@endpush
@endsection
