<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>@yield('title', 'AquaHeart - Water Refilling Management')</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        html, body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f5f5f5;
            color: #333;
        }

        nav {
            background: linear-gradient(135deg, #0ea5a4 0%, #0d8885 100%);
            padding: 0 20px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        nav a.brand {
            color: white;
            text-decoration: none;
            font-weight: bold;
            font-size: 1.5em;
            padding: 15px 0;
            display: flex;
            align-items: center;
        }

        nav a.brand:hover {
            opacity: 0.9;
        }

        nav ul {
            list-style: none;
            display: flex;
            gap: 20px;
        }

        nav ul li a {
            color: white;
            text-decoration: none;
            padding: 8px 12px;
            border-radius: 4px;
            transition: background-color 0.3s ease;
        }

        nav ul li a:hover {
            background-color: rgba(255,255,255,0.2);
        }

        main {
            max-width: 1400px;
            margin: 20px auto;
            padding: 0 20px;
            background: white;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            min-height: calc(100vh - 120px);
        }

        footer {
            background: #f5f5f5;
            padding: 20px;
            text-align: center;
            color: #666;
            margin-top: 40px;
            border-top: 1px solid #ddd;
        }

        .logout-trigger {
            background: none;
            border: none;
            color: white;
            cursor: pointer;
            padding: 8px 12px;
            font-size: 1em;
            text-decoration: none;
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
            border-radius: 16px;
            padding: 28px;
            box-shadow: 0 24px 60px rgba(15, 23, 42, 0.22);
        }

        .modal-card h3 {
            font-size: 1.2rem;
            margin-bottom: 8px;
            color: #0f172a;
        }

        .modal-card p {
            color: #64748b;
            line-height: 1.6;
        }

        .modal-actions {
            display: flex;
            justify-content: flex-end;
            gap: 12px;
            margin-top: 24px;
        }

        .btn-secondary {
            background: #ffffff;
            border: 1px solid #ddd;
            color: #333;
            border-radius: 8px;
            padding: 10px 16px;
            cursor: pointer;
            font-weight: 600;
        }

        .btn-danger {
            background: #dc2626;
            border: none;
            color: #ffffff;
            border-radius: 8px;
            padding: 10px 16px;
            cursor: pointer;
            font-weight: 600;
        }

        @media (max-width: 768px) {
            nav {
                flex-direction: column;
                gap: 15px;
            }

            nav ul {
                flex-wrap: wrap;
                justify-content: center;
            }

            main {
                margin: 10px;
            }
        }
    </style>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>
<body>
    <nav>
        <a href="{{ route('aquaheart.dashboard') }}" class="brand">🌊 AQUAHEART</a>
        <ul>
            <li><a href="{{ route('aquaheart.dashboard') }}">Dashboard</a></li>
            <li><a href="{{ route('aquaheart.customers.index') }}">Customers</a></li>
            <li><a href="{{ route('aquaheart.refills.index') }}">Refills</a></li>
            <li><a href="{{ route('aquaheart.reports.sales') }}">📊 Reports</a></li>
            <li><form method="POST" action="{{ route('logout') }}" style="display:inline;" id="logoutForm">
                @csrf
                <button type="button" class="logout-trigger" data-logout-open>Logout</button>
            </form></li>
        </ul>
    </nav>

    <main>
        @yield('content')
    </main>

    <footer>
        <p>&copy; 2026 AQUAHEART - Water Refilling Station Management System</p>
    </footer>

    <div class="modal-overlay" id="logoutModal" aria-hidden="true">
        <div class="modal-card" role="dialog" aria-modal="true" aria-labelledby="logoutModalTitle">
            <h3 id="logoutModalTitle">Confirm logout</h3>
            <p>You are about to sign out of AquaHeart. Do you want to continue?</p>
            <div class="modal-actions">
                <button type="button" class="btn-secondary" data-logout-cancel>Cancel</button>
                <button type="button" class="btn-danger" data-logout-confirm>Log out</button>
            </div>
        </div>
    </div>

    <script src="{{ asset('js/app.js') }}"></script>
    <script>
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
</body>
</html>
