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
            padding: 32px 24px;
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .brand-icon {
            width: 32px;
            height: 32px;
            background: var(--primary);
            color: white;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .brand-name {
            font-weight: 800;
            font-size: 1.15rem;
            letter-spacing: -0.5px;
            color: var(--primary);
            text-transform: none; /* Removed OA uppercase */
        }

        .nav-section {
            padding: 0 12px;
            margin-top: 10px;
        }

        .nav-label {
            padding: 0 16px;
            font-size: 0.7rem;
            font-weight: 700;
            color: var(--text-muted);
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 8px;
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
            width: 18px;
            height: 18px;
            opacity: 0.8;
        }

        .sidebar-footer {
            margin-top: auto;
            padding: 20px;
            border-top: 1px solid var(--border);
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
            border-radius: 200px;
            width: 350px;
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

        @media (max-width: 1024px) {
            .sidebar { width: 80px; }
            .brand-name, .nav-link span, .nav-label, .sidebar-footer { display: none; }
            .main { margin-left: 80px; }
            .nav-link { justify-content: center; }
        }

        @stack('styles')
    </style>
</head>
<body>

    <aside class="sidebar">
        <div class="sidebar-brand">
            <div class="brand-icon">
                <i data-lucide="droplet"></i>
            </div>
            <div class="brand-name">AquaHeart Admin</div>
        </div>

        <div class="nav-section">
            <div class="nav-label">Main Menu</div>
            <ul class="nav-list">
                <li class="nav-item">
                    <a href="{{ route('aquaheart.dashboard') }}" class="nav-link {{ request()->routeIs('aquaheart.dashboard') ? 'active' : '' }}">
                        <i data-lucide="grid"></i>
                        <span>Dashboard</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('aquaheart.customers.index') }}" class="nav-link {{ request()->routeIs('aquaheart.customers.*') ? 'active' : '' }}">
                        <i data-lucide="users"></i>
                        <span>Customers</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('aquaheart.products.index') }}" class="nav-link {{ request()->routeIs('aquaheart.products.*') ? 'active' : '' }}">
                        <i data-lucide="package-2"></i>
                        <span>Products</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('aquaheart.refills.index') }}" class="nav-link {{ request()->routeIs('aquaheart.refills.*') ? 'active' : '' }}">
                        <i data-lucide="columns"></i>
                        <span>Refill Logs</span>
                    </a>
                </li>
            </ul>

            <div class="nav-label">Analysis</div>
            <ul class="nav-list">
                <li class="nav-item">
                    <a href="{{ route('aquaheart.reports.sales') }}" class="nav-link {{ request()->routeIs('aquaheart.reports.*') ? 'active' : '' }}">
                        <i data-lucide="pie-chart"></i>
                        <span>Reports</span>
                    </a>
                </li>
            </ul>

            <div class="nav-label">Support</div>
            <ul class="nav-list">
                <li class="nav-item">
                    <a href="{{ route('aquaheart.reports.customers') }}" class="nav-link {{ request()->routeIs('aquaheart.reports.customers') ? 'active' : '' }}">
                        <i data-lucide="settings"></i>
                        <span>Customer Stats</span>
                    </a>
                </li>
            </ul>
        </div>

        <div class="sidebar-footer">
            <form method="POST" action="{{ route('logout') }}" id="logoutForm">
                @csrf
                <button type="button" class="logout-trigger" data-logout-open>
                    <div class="nav-link" style="color: #ef4444; border: 1px solid #fee2e2;">
                        <i data-lucide="log-out"></i>
                        <span>Sign Out</span>
                    </div>
                </button>
            </form>
        </div>
    </aside>

    <main class="main">
        <div class="topbar">
            <div class="search">
                <i data-lucide="search" size="16" style="color: #94a3b8;"></i>
                <input type="text" placeholder="Search data...">
            </div>
            <div class="topbar-right">
                <div class="user-pill">
                    <i data-lucide="shield" size="14"></i>
                    <span>{{ auth()->user()->name ?? 'Administrator' }}</span>
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
        <div class="modal-card" role="dialog" aria-modal="true" aria-labelledby="logoutModalTitle">
            <h3 id="logoutModalTitle">Confirm logout</h3>
            <p>You are about to sign out of the AquaHeart admin panel. Do you want to continue?</p>
            <div class="modal-actions">
                <button type="button" class="btn-secondary" data-logout-cancel>Cancel</button>
                <button type="button" class="btn-danger" data-logout-confirm>Log out</button>
            </div>
        </div>
    </div>

    <script>
        lucide.createIcons();

        const logoutModal = document.getElementById('logoutModal');
        const logoutForm = document.getElementById('logoutForm');
        const logoutOpenButtons = document.querySelectorAll('[data-logout-open]');
        const logoutCancelButton = document.querySelector('[data-logout-cancel]');
        const logoutConfirmButton = document.querySelector('[data-logout-confirm]');

        function openLogoutModal() {
            logoutModal.classList.add('show');
            logoutModal.setAttribute('aria-hidden', 'false');
        }

        function closeLogoutModal() {
            logoutModal.classList.remove('show');
            logoutModal.setAttribute('aria-hidden', 'true');
        }

        logoutOpenButtons.forEach((button) => {
            button.addEventListener('click', openLogoutModal);
        });

        logoutCancelButton?.addEventListener('click', closeLogoutModal);
        logoutConfirmButton?.addEventListener('click', () => logoutForm.submit());

        logoutModal?.addEventListener('click', (event) => {
            if (event.target === logoutModal) {
                closeLogoutModal();
            }
        });

        document.addEventListener('keydown', (event) => {
            if (event.key === 'Escape' && logoutModal.classList.contains('show')) {
                closeLogoutModal();
            }
        });
    </script>
    @stack('scripts')
</body>
</html>
