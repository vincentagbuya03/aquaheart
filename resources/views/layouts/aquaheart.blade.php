<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin') | Aqua Heart</title>
    
    <!-- Modern Professional Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Icons -->
    <script src="https://unpkg.com/lucide@latest"></script>
    
    <!-- DotLottie Player (Required for .lottie files) -->
    <script src="https://unpkg.com/@dotlottie/player-component@latest/dist/dotlottie-player.mjs" type="module"></script>
    
    <!-- Lottie FilesFallback for JSON -->
    <script src="https://unpkg.com/@lottiefiles/lottie-player@latest/dist/lottie-player.js"></script>
    
    @stack('scripts_header')

    <style>
        :root {
            --primary: #0f172a;
            --accent: #3b82f6;
            --accent-soft: rgba(59, 130, 246, 0.08);
            --bg: #f8fafc;
            --card-bg: #ffffff;
            --text-main: #1e293b;
            --text-muted: #64748b;
            --border: #e2e8f0;
            --sidebar-width: 260px;
            --radius: 12px;
            --transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: var(--bg);
            color: var(--text-main);
            min-height: 100vh;
            display: flex;
            -webkit-font-smoothing: antialiased;
        }

        /* --- Sidebar --- */
        .sidebar {
            width: var(--sidebar-width);
            background: #ffffff;
            border-right: 1px solid var(--border);
            height: 100vh;
            position: fixed;
            left: 0;
            top: 0;
            display: flex;
            flex-direction: column;
            z-index: 1000;
        }

        .sidebar-brand {
            padding: 24px;
            display: flex;
            align-items: center;
            gap: 12px;
            border-bottom: 1px solid var(--border);
            margin-bottom: 8px;
        }

        .brand-logo-img {
            width: 36px;
            height: 36px;
            object-fit: contain;
            border-radius: 8px;
            filter: drop-shadow(0 2px 4px rgba(0,0,0,0.05));
        }

        .brand-name {
            font-weight: 800;
            font-size: 1.1rem;
            letter-spacing: -0.5px;
            color: var(--primary);
            line-height: 1.2;
        }

        .brand-name span {
            display: block;
            font-size: 0.7rem;
            font-weight: 700;
            color: var(--accent);
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        .nav-section {
            padding: 0 12px;
            margin-top: 10px;
        }

        .nav-label {
            padding: 0 16px;
            font-size: 0.65rem;
            font-weight: 800;
            color: var(--text-muted);
            text-transform: uppercase;
            letter-spacing: 1.2px;
            margin: 20px 0 10px;
        }

        .nav-list {
            list-style: none;
            margin-bottom: 24px;
        }

        .nav-link {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 10px 16px;
            color: var(--text-muted);
            text-decoration: none;
            border-radius: 10px;
            font-weight: 600;
            font-size: 0.9rem;
            transition: var(--transition);
        }

        .nav-link:hover {
            color: var(--accent);
            background: var(--bg);
        }

        .nav-link.active {
            background: var(--bg);
            color: var(--accent);
            border: 1px solid var(--border);
        }

        .nav-link i {
            width: 20px;
            height: 20px;
            stroke-width: 2.5;
            opacity: 0.9;
        }

        .sidebar-footer {
            margin-top: auto;
            padding: 24px 16px;
            border-top: 1px solid var(--border);
            background: #fafafa;
        }

        .nav-link-logout {
            color: #dc2626 !important;
            border: 1px solid #fee2e2 !important;
            background: #fff5f5;
        }

        .nav-link-logout:hover {
            background: #fee2e2 !important;
            color: #b91c1c !important;
        }

        /* --- Main Layout --- */
        .main {
            margin-left: var(--sidebar-width);
            flex: 1;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }

        .topbar {
            height: 70px;
            background: #ffffff;
            border-bottom: 1px solid var(--border);
            display: flex;
            align-items: center;
            padding: 0 40px;
            justify-content: space-between;
            position: sticky;
            top: 0;
            z-index: 900;
        }

        .search {
            display: flex;
            align-items: center;
            gap: 12px;
            background: #f1f5f9;
            padding: 8px 16px;
            border-radius: 12px;
            width: 400px;
            border: 1px solid transparent;
            transition: var(--transition);
        }

        .search:focus-within {
            background: #ffffff;
            border-color: var(--accent);
            box-shadow: 0 4px 12px rgba(59, 130, 246, 0.08);
            width: 420px;
        }

        .search input {
            border: none;
            background: transparent;
            font-family: inherit;
            font-size: 0.85rem;
            width: 100%;
            outline: none;
        }

        .topbar-right {
            display: flex;
            align-items: center;
            gap: 20px;
        }

        .user-pill {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 6px 12px;
            background: var(--accent-soft);
            border-radius: 100px;
            color: var(--accent);
            font-weight: 700;
            font-size: 0.85rem;
        }

        .topbar-right i {
            stroke-width: 2.5;
        }

        .content {
            padding: 40px;
            flex: 1;
        }

        /* --- Professional Components --- */
        .section-header {
            margin-bottom: 32px;
            display: flex;
            justify-content: space-between;
            align-items: flex-end;
        }

        .section-title h1 {
            font-size: 1.5rem;
            font-weight: 800;
            color: var(--primary);
            letter-spacing: -0.5px;
        }

        .section-title p {
            color: var(--text-muted);
            font-size: 0.9rem;
            margin-top: 4px;
        }

        .btn-primary {
            background: var(--primary);
            color: white;
            padding: 10px 20px;
            border-radius: 10px;
            font-weight: 600;
            font-size: 0.85rem;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            border: none;
            cursor: pointer;
            transition: var(--transition);
        }

        .btn-primary:hover {
            transform: translateY(-1px);
            opacity: 0.9;
        }

        .btn-primary i, .btn-premium i, .btn-done i {
            stroke-width: 2.5;
        }

        .card {
            background: #ffffff;
            border: 1px solid var(--border);
            border-radius: var(--radius);
            padding: 24px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.05);
        }

        .logout-trigger {
            background: none;
            border: none;
            width: 100%;
            text-align: left;
            cursor: pointer;
            padding: 0;
            display: block;
        }

        .modal-overlay {
            position: fixed;
            inset: 0;
            background: rgba(15, 23, 42, 0.58);
            display: none;
            align-items: center;
            justify-content: center;
            padding: 24px;
            z-index: 2000;
        }

        .modal-overlay.show {
            display: flex;
        }

        .modal-card {
            width: min(420px, 100%);
            background: #ffffff;
            border-radius: 20px;
            padding: 28px;
            box-shadow: 0 24px 60px rgba(15, 23, 42, 0.22);
        }

        .modal-card h3 {
            font-size: 1.2rem;
            font-weight: 800;
            color: var(--primary);
            margin-bottom: 8px;
        }

        .modal-card p {
            color: var(--text-muted);
            font-size: 0.92rem;
            line-height: 1.6;
        }

        .modal-actions {
            margin-top: 24px;
            display: flex;
            justify-content: flex-end;
            gap: 12px;
        }

        .btn-secondary {
            background: #ffffff;
            color: var(--text-main);
            border: 1px solid var(--border);
            padding: 10px 18px;
            border-radius: 10px;
            font-weight: 700;
            cursor: pointer;
            transition: var(--transition);
        }

        .btn-danger {
            background: #dc2626;
            color: #ffffff;
            border: none;
            padding: 10px 18px;
            border-radius: 10px;
            font-weight: 700;
            cursor: pointer;
            transition: var(--transition);
        }

        .btn-secondary:hover,
        .btn-danger:hover {
            transform: translateY(-1px);
        }

        /* --- Success Modal --- */
        .success-modal-card {
            width: min(400px, 100%);
            background: #ffffff;
            border-radius: 24px;
            padding: 40px 32px;
            text-align: center;
            box-shadow: 0 24px 60px rgba(15, 23, 42, 0.22);
            animation: modalPop 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        }

        @keyframes modalPop {
            from { opacity: 0; transform: scale(0.8) translateY(20px); }
            to { opacity: 1; transform: scale(1) translateY(0); }
        }

        /* --- Success Modal Variants --- */
        .success-modal-card.style-delete {
            border-top: 5px solid #dc2626;
        }
        .success-modal-card.style-update {
            border-top: 5px solid #3b82f6;
        }
        .success-modal-card.style-create {
            border-top: 5px solid #10b981;
        }

        .lottie-container {
            width: 130px;
            height: 130px;
            margin: 0 auto 16px;
        }

        .success-modal-card h2 {
            font-size: 1.5rem;
            font-weight: 800;
            color: var(--primary);
            margin-bottom: 8px;
        }

        .success-modal-card p {
            color: var(--text-muted);
            font-size: 0.95rem;
            line-height: 1.6;
            margin-bottom: 32px;
        }

        .btn-done {
            background: var(--accent);
            color: white;
            padding: 12px 32px;
            border-radius: 12px;
            font-weight: 700;
            font-size: 0.95rem;
            border: none;
            cursor: pointer;
            width: 100%;
            transition: var(--transition);
        }

        .btn-done:hover {
            background: #2563eb;
            box-shadow: 0 8px 20px rgba(59, 130, 246, 0.25);
        }

        .ajax-toast {
            position: fixed;
            right: 24px;
            bottom: 24px;
            min-width: 260px;
            max-width: 380px;
            background: #0f172a;
            color: #ffffff;
            border-radius: 12px;
            padding: 12px 14px;
            border: 1px solid rgba(255, 255, 255, 0.08);
            box-shadow: 0 16px 40px rgba(15, 23, 42, 0.28);
            z-index: 2500;
            opacity: 0;
            transform: translateY(8px);
            pointer-events: none;
            transition: opacity 0.2s ease, transform 0.2s ease;
            font-size: 0.85rem;
            font-weight: 700;
            line-height: 1.35;
        }

        .ajax-toast.show {
            opacity: 1;
            transform: translateY(0);
        }

        .ajax-toast.error {
            background: #7f1d1d;
        }

        @media (max-width: 1024px) {
            .sidebar { width: 80px; }
            .brand-name, .nav-link span, .nav-label, .sidebar-footer { display: none; }
            .main { margin-left: 80px; }
            .nav-link { justify-content: center; }
        }

        /* Quick Action Menu */
        .quick-action-menu {
            position: absolute;
            top: 100%;
            right: 0;
            background: white;
            border: 1px solid var(--border);
            border-radius: 12px;
            box-shadow: 0 12px 32px rgba(15, 23, 42, 0.12);
            min-width: 220px;
            margin-top: 8px;
            z-index: 1001;
            animation: slideDown 0.2s ease-out;
        }

        @keyframes slideDown {
            from {
                opacity: 0;
                transform: translateY(-8px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .quick-action-item {
            border-bottom: 1px solid var(--border);
        }

        .quick-action-item:last-child {
            border-bottom: none;
        }

        .quick-action-link {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px 16px;
            color: var(--text-main);
            text-decoration: none;
            font-weight: 600;
            font-size: 0.9rem;
            transition: all 0.2s;
        }

        .quick-action-link:hover {
            background: var(--accent-soft);
            color: var(--accent);
        }

        .quick-action-link i {
            width: 18px;
            height: 18px;
            stroke-width: 2.5;
        }

        @stack('styles')
    </style>
</head>
<body>

    <aside class="sidebar">
        <a href="{{ auth()->user()->is_admin ? route('aquaheart.dashboard') : route('aquaheart.cashier.dashboard') }}" class="sidebar-brand" style="text-decoration: none; display: flex; transition: var(--transition);">
            <img src="{{ asset('logo.png') }}" alt="Aqua Heart Logo" class="brand-logo-img">
            <div class="brand-name">
                Aqua Heart
                <span>{{ auth()->user()->is_admin ? 'Admin Panel' : 'Cashier Portal' }}</span>
            </div>
        </a>

        <div class="nav-section" style="flex: 1; overflow-y: auto; padding-bottom: 20px;">
            <div class="nav-label">General</div>
            <ul class="nav-list">
                <li class="nav-item">
                    <a href="{{ auth()->user()->is_admin ? route('aquaheart.dashboard') : route('aquaheart.cashier.dashboard') }}" class="nav-link {{ request()->routeIs('aquaheart.dashboard') || request()->routeIs('aquaheart.cashier.dashboard') ? 'active' : '' }}">
                        <i data-lucide="layout-dashboard"></i>
                        <span>Dashboard</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('aquaheart.reports.sales') }}" class="nav-link {{ request()->routeIs('aquaheart.reports.sales') ? 'active' : '' }}">
                        <i data-lucide="bar-chart-3"></i>
                        <span>Sales Monitor</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('aquaheart.products.index') }}" class="nav-link {{ request()->routeIs('aquaheart.products.*') ? 'active' : '' }}">
                        <i data-lucide="boxes"></i>
                        <span>Inventory</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('aquaheart.customers.index') }}" class="nav-link {{ request()->routeIs('aquaheart.customers.*') ? 'active' : '' }}">
                        <i data-lucide="users"></i>
                        <span>Customers</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('aquaheart.refills.index') }}" class="nav-link {{ request()->routeIs('aquaheart.refills.index') ? 'active' : '' }}">
                        <i data-lucide="receipt"></i>
                        <span>Transaction Logs</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('aquaheart.logs.index') }}" class="nav-link {{ request()->routeIs('aquaheart.logs.*') ? 'active' : '' }}">
                        <i data-lucide="history"></i>
                        <span>Activity Logs</span>
                    </a>
                </li>
            </ul>

            @if(auth()->user()->is_admin)
                <div class="nav-label">Management</div>
                <ul class="nav-list">
                    <li class="nav-item">
                        <a href="{{ route('aquaheart.users.index') }}" class="nav-link {{ request()->routeIs('aquaheart.users.*') ? 'active' : '' }}">
                            <i data-lucide="shield-check"></i>
                            <span>Team Management</span>
                        </a>
                    </li>
                </ul>
            @endif
        </div>

        <div class="sidebar-footer">
            <div style="margin-bottom: 20px;">
                <a href="{{ route('aquaheart.refills.create') }}" class="btn-primary" style="width: 100%; justify-content: center; height: 44px; font-weight: 700; border-radius: 12px; box-shadow: 0 4px 12px rgba(15, 23, 42, 0.1);">
                    <i data-lucide="plus-circle" size="18"></i>
                    New Transaction
                </a>
            </div>

            <ul class="nav-list" style="margin-bottom: 0;">
                <li class="nav-item">
                    <a href="{{ route('aquaheart.cashier.settings.index') }}" class="nav-link {{ request()->routeIs('aquaheart.cashier.settings.*') ? 'active' : '' }}">
                        <i data-lucide="settings"></i>
                        <span>Settings</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="#" class="nav-link">
                        <i data-lucide="help-circle"></i>
                        <span>Support</span>
                    </a>
                </li>
                <li class="nav-item" style="margin-top: 8px;">
                    <form method="POST" action="{{ route('logout') }}" id="logoutForm">
                        @csrf
                        <button type="button" class="logout-trigger" data-logout-open>
                            <div class="nav-link nav-link-logout" style="justify-content: center;">
                                <i data-lucide="log-out"></i>
                                <span>Sign Out</span>
                            </div>
                        </button>
                    </form>
                </li>
            </ul>
        </div>
    </aside>

    <main class="main">
        <div class="topbar">
            <form action="{{ route('aquaheart.products.index') }}" method="GET" class="search">
                <i data-lucide="search" size="16" style="color: #94a3b8;"></i>
                <input type="text" name="search" placeholder="Search inventory items..." value="{{ request('search') }}">
            </form>
            <div class="topbar-right" style="display: flex; align-items: center; gap: 24px;">
                <a href="#" style="display: flex; align-items: center; gap: 6px; color: var(--text-muted); text-decoration: none; font-weight: 600; font-size: 0.85rem;">
                    <i data-lucide="help-circle" size="18"></i> Help
                </a>
                <button id="quickActionBtn" class="btn-primary" style="padding: 8px 16px; gap: 8px; display: flex; align-items: center; border: none; cursor: pointer;">
                    <i data-lucide="zap" size="16" style="stroke-width: 2.5;"></i> Quick Action
                </button>
                
                <!-- Quick Action Menu -->
                <div id="quickActionMenu" class="quick-action-menu" style="display: none;">
                    <div class="quick-action-item">
                        <a href="{{ route('aquaheart.refills.create') }}" class="quick-action-link">
                            <i data-lucide="plus-circle"></i>
                            <span>New Transaction</span>
                        </a>
                    </div>
                    <div class="quick-action-item">
                        <a href="{{ route('aquaheart.products.create') }}" class="quick-action-link">
                            <i data-lucide="package-plus"></i>
                            <span>Add Product</span>
                        </a>
                    </div>
                    <div class="quick-action-item">
                        <a href="{{ route('aquaheart.customers.index') }}" class="quick-action-link">
                            <i data-lucide="user-plus"></i>
                            <span>View Customers</span>
                        </a>
                    </div>
                    <div class="quick-action-item">
                        <a href="{{ route('aquaheart.reports.sales') }}" class="quick-action-link">
                            <i data-lucide="bar-chart-3"></i>
                            <span>Sales Report</span>
                        </a>
                    </div>
                </div>
                
                <div style="width: 1px; height: 24px; background: var(--border);"></div>
                
                <button style="background: none; border: none; cursor: pointer; color: var(--text-muted); position: relative;">
                    <i data-lucide="bell" size="20"></i>
                    <span style="position: absolute; top: 0; right: 0; width: 8px; height: 8px; background: #ef4444; border-radius: 50%; border: 2px solid white;"></span>
                </button>
                
                <div style="display: flex; align-items: center; gap: 12px; margin-left: 8px;">
                    <div style="text-align: right; display: flex; flex-direction: column;">
                        <span style="font-weight: 800; font-size: 0.85rem; color: var(--primary);">{{ auth()->user()->name ?? 'Admin' }}</span>
                        <span style="font-size: 0.65rem; font-weight: 700; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.5px;">{{ auth()->user()->is_admin ? 'Manager' : 'Cashier' }}</span>
                    </div>
                    <img src="https://ui-avatars.com/api/?name={{ urlencode(auth()->user()->name ?? 'Admin') }}&background=e0f2fe&color=0369a1" alt="Profile" style="width: 36px; height: 36px; border-radius: 10px; object-fit: cover;">
                </div>
            </div>
        </div>

        <div class="content">
            <div class="section-header">
                <div class="section-title">
                    <h1>@yield('page_title')</h1>
                    <p>@yield('page_subtitle')</p>
                </div>
                <div class="section-actions">
                    @yield('page_actions')
                </div>
            </div>

            @yield('content')
        </div>
    </main>

    <div class="modal-overlay" id="logoutModal" aria-hidden="true">
        <div class="modal-card" style="text-align: center; padding: 40px 32px; border-radius: 24px;" role="dialog" aria-modal="true" aria-labelledby="logoutModalTitle">
            <div style="width: 130px; height: 130px; margin: 0 auto 10px;">
                <dotlottie-player 
                    src="{{ asset('lottie/Warning animation.lottie') }}" 
                    background="transparent" 
                    speed="1" 
                    style="width: 130px; height: 130px;" 
                    loop 
                    autoplay>
                </dotlottie-player>
            </div>
            <h3 id="logoutModalTitle" style="font-size: 1.4rem; font-weight: 800; color: var(--primary); margin-bottom: 12px;">Confirm Logout</h3>
            <p style="color: var(--text-muted); margin-bottom: 32px;">You are about to sign out of the AquaHeart {{ auth()->user()->is_admin ? 'admin' : 'cashier' }} panel. Do you want to continue?</p>
            <div class="modal-actions" style="display: flex; gap: 12px; justify-content: center;">
                <button type="button" class="btn-secondary" style="padding: 12px 24px; border-radius: 12px; font-weight: 700; border: 1px solid var(--border); background: white;" data-logout-cancel>Cancel</button>
                <button type="button" class="btn-danger" style="padding: 12px 24px; border-radius: 12px; font-weight: 700; background: #dc2626; color: white; border: none;" data-logout-confirm>Log out</button>
            </div>
        </div>
    </div>

    <div class="modal-overlay" id="deleteModal" aria-hidden="true">
        <div class="modal-card" style="text-align: center; padding: 40px 32px; border-radius: 24px;" role="dialog" aria-modal="true" aria-labelledby="deleteModalTitle">
            <div style="width: 130px; height: 130px; margin: 0 auto 10px;">
                <dotlottie-player
                    src="{{ asset('lottie/Warning animation.lottie') }}"
                    background="transparent"
                    speed="1"
                    style="width: 130px; height: 130px;"
                    loop
                    autoplay>
                </dotlottie-player>
            </div>
            <h3 id="deleteModalTitle" style="font-size: 1.4rem; font-weight: 800; color: var(--primary); margin-bottom: 12px;">Confirm Delete</h3>
            <p id="deleteModalMessage" style="color: var(--text-muted); margin-bottom: 32px;">Are you sure you want to delete this record?</p>
            <div class="modal-actions" style="display: flex; gap: 12px; justify-content: center;">
                <button type="button" class="btn-secondary" style="padding: 12px 24px; border-radius: 12px; font-weight: 700; border: 1px solid var(--border); background: white;" data-delete-cancel>Cancel</button>
                <button type="button" class="btn-danger" style="padding: 12px 24px; border-radius: 12px; font-weight: 700; background: #dc2626; color: white; border: none;" data-delete-confirm>Delete</button>
            </div>
        </div>
    </div>

    @if(session('success'))
    @php
        $msg = strtolower(session('success'));
        $type = 'create';
        $lottie = asset('lottie/success.lottie'); 
        $title = 'Success!';
        
        if (str_contains($msg, 'update') || str_contains($msg, 'edit')) {
            $type = 'update';
        $lottie = asset('lottie/success.lottie'); 
            $title = 'Updated!';
        } elseif (str_contains($msg, 'delete') || str_contains($msg, 'remove')) {
            $type = 'delete';
            $lottie = asset('lottie/Delete Icon.lottie'); 
            $title = 'Deleted!';
        }
    @endphp
    <div class="modal-overlay show" id="successModal">
        <div class="success-modal-card style-{{ $type }}">
            <div class="lottie-container">
                <dotlottie-player 
                    src="{{ $lottie }}" 
                    background="transparent" 
                    speed="1" 
                    style="width: 130px; height: 130px; margin: 0 auto;" 
                    autoplay>
                </dotlottie-player>
            </div>
            <h2 style="margin-top: 5px;">{{ $title }}</h2>
            <p>{{ session('success') }}</p>
            <button type="button" class="btn-done" style="background: {{ $type == 'delete' ? '#dc2626' : ($type == 'update' ? '#3b82f6' : '#10b981') }};" onclick="document.getElementById('successModal').classList.remove('show')">
                Got it
            </button>
        </div>
    </div>
    @endif

    <script>
        // Check if lottie-player is defined, if not, try a fallback or log
        window.addEventListener('load', () => {
            if (!customElements.get('lottie-player')) {
                console.error('Lottie Player failed to load. Check your internet connection or script source.');
            }
        });
    </script>

    <script>
        lucide.createIcons();

        const logoutModal = document.getElementById('logoutModal');
        const logoutForm = document.getElementById('logoutForm');
        const logoutOpenButtons = document.querySelectorAll('[data-logout-open]');
        const logoutCancelButton = document.querySelector('[data-logout-cancel]');
        const logoutConfirmButton = document.querySelector('[data-logout-confirm]');
        const deleteModal = document.getElementById('deleteModal');
        const deleteModalMessage = document.getElementById('deleteModalMessage');
        const deleteCancelButton = document.querySelector('[data-delete-cancel]');
        const deleteConfirmButton = document.querySelector('[data-delete-confirm]');

        let deleteResolve = null;

        function openLogoutModal() {
            logoutModal.classList.add('show');
            logoutModal.setAttribute('aria-hidden', 'false');
        }

        function closeLogoutModal() {
            logoutModal.classList.remove('show');
            logoutModal.setAttribute('aria-hidden', 'true');
        }

        function openDeleteModal(message) {
            deleteModalMessage.textContent = message || 'Are you sure you want to delete this record?';
            deleteModal.classList.add('show');
            deleteModal.setAttribute('aria-hidden', 'false');
        }

        function closeDeleteModal() {
            deleteModal.classList.remove('show');
            deleteModal.setAttribute('aria-hidden', 'true');
        }

        function askDeleteConfirmation(message) {
            openDeleteModal(message);

            return new Promise((resolve) => {
                deleteResolve = resolve;
            });
        }

        function resolveDeleteModal(confirmed) {
            if (typeof deleteResolve === 'function') {
                deleteResolve(confirmed);
                deleteResolve = null;
            }

            closeDeleteModal();
        }

        logoutOpenButtons.forEach((button) => {
            button.addEventListener('click', openLogoutModal);
        });

        logoutCancelButton?.addEventListener('click', closeLogoutModal);
        logoutConfirmButton?.addEventListener('click', () => logoutForm.submit());
        deleteCancelButton?.addEventListener('click', () => resolveDeleteModal(false));
        deleteConfirmButton?.addEventListener('click', () => resolveDeleteModal(true));

        logoutModal?.addEventListener('click', (event) => {
            if (event.target === logoutModal) {
                closeLogoutModal();
            }
        });

        deleteModal?.addEventListener('click', (event) => {
            if (event.target === deleteModal) {
                resolveDeleteModal(false);
            }
        });

        document.addEventListener('keydown', (event) => {
            if (event.key === 'Escape' && logoutModal.classList.contains('show')) {
                closeLogoutModal();
            }

            if (event.key === 'Escape' && deleteModal.classList.contains('show')) {
                resolveDeleteModal(false);
            }
        });

        (function () {
            let toastTimeout;

            function isDeleteForm(form) {
                const methodInput = form.querySelector('input[name="_method"]');
                return !!methodInput && String(methodInput.value || '').toUpperCase() === 'DELETE';
            }

            function showAjaxToast(message, isError) {
                let toast = document.getElementById('ajaxToast');

                if (!toast) {
                    toast = document.createElement('div');
                    toast.id = 'ajaxToast';
                    toast.className = 'ajax-toast';
                    document.body.appendChild(toast);
                }

                toast.textContent = message;
                toast.classList.toggle('error', !!isError);
                toast.classList.add('show');

                window.clearTimeout(toastTimeout);
                toastTimeout = window.setTimeout(() => {
                    toast.classList.remove('show');
                }, 2400);
            }

            document.addEventListener('submit', async (event) => {
                const form = event.target;
                if (!(form instanceof HTMLFormElement)) {
                    return;
                }

                if (!isDeleteForm(form)) {
                    return;
                }

                event.preventDefault();

                if (form.dataset.deleteConfirmed === '1') {
                    delete form.dataset.deleteConfirmed;
                    return;
                }

                const deleteLabel = form.getAttribute('data-delete-label') || '';
                const message = form.getAttribute('data-confirm') || (deleteLabel ? `Delete ${deleteLabel}?` : 'Delete this record?');
                const confirmed = await askDeleteConfirmation(message);

                if (!confirmed) {
                    return;
                }

                if (!form.hasAttribute('data-ajax-delete')) {
                    form.dataset.deleteConfirmed = '1';
                    form.submit();
                    return;
                }

                const submitButton = form.querySelector('button[type="submit"]');
                const previousDisabledState = submitButton ? submitButton.disabled : false;

                if (submitButton) {
                    submitButton.disabled = true;
                }

                const formData = new FormData(form);

                try {
                    const response = await fetch(form.action, {
                        method: 'POST',
                        headers: {
                            'Accept': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest',
                        },
                        body: formData,
                    });

                    const payload = await response.json().catch(() => ({}));

                    if (!response.ok) {
                        throw new Error(payload.message || 'Unable to process this request right now.');
                    }

                    const row = form.closest('tr');
                    if (row) {
                        row.remove();
                    }

                    showAjaxToast(payload.message || 'Deleted successfully.', false);

                    const tableBody = form.closest('table')?.querySelector('tbody');
                    if (tableBody && tableBody.children.length === 0) {
                        window.location.reload();
                    }
                } catch (error) {
                    showAjaxToast(error.message || 'Delete failed.', true);
                } finally {
                    if (submitButton) {
                        submitButton.disabled = previousDisabledState;
                    }
                }
            });
        })();
    </script>
    @stack('scripts')
</body>
</html>
