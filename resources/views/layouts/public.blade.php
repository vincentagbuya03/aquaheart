<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Aqua Heart Purified Drinking Water') | Guaranteed Safe and Clean from Pure Heart Within</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=Manrope:wght@700;800&display=swap" rel="stylesheet">
    
    <!-- Icons -->
    <script src="https://unpkg.com/lucide@latest"></script>
    
    <!-- DotLottie Player -->
    <script src="https://unpkg.com/@dotlottie/player-component@latest/dist/dotlottie-player.mjs" type="module"></script>

    <style>
        :root {
            --aqua-50: #f0f9ff;
            --aqua-100: #e0f2fe;
            --aqua-400: #38bdf8;
            --aqua-600: #0284c7;
            --aqua-900: #0c4a6e;
            --slate-900: #0f172a;
            --slate-700: #334155;
            --white: #ffffff;
            --radius-xl: 32px;
            --radius-lg: 20px;
            --transition: all 0.6s cubic-bezier(0.16, 1, 0.3, 1);
        }

        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            position: relative;
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: var(--white);
            color: var(--slate-900);
            overflow-x: hidden;
            line-height: 1.6;
        }

        body::before,
        body::after {
            content: '';
            position: fixed;
            inset: auto;
            width: 28rem;
            height: 28rem;
            border-radius: 50%;
            pointer-events: none;
            z-index: 0;
            opacity: 0.25;
            filter: blur(16px);
            animation: drift 18s ease-in-out infinite;
        }

        body::before {
            top: -8rem;
            right: -6rem;
            background: radial-gradient(circle, rgba(56, 189, 248, 0.18) 0%, rgba(56, 189, 248, 0) 68%);
        }

        body::after {
            bottom: -10rem;
            left: -8rem;
            background: radial-gradient(circle, rgba(2, 132, 199, 0.12) 0%, rgba(2, 132, 199, 0) 70%);
            animation-delay: -9s;
        }

        main {
            padding-top: 112px;
            position: relative;
            z-index: 1;
            animation: pageIn 0.8s ease-out both;
        }

        .reveal { opacity: 0; transform: translateY(30px); transition: var(--transition); }
        .reveal.active { opacity: 1; transform: translateY(0); }

        .container { max-width: 1280px; margin: 0 auto; padding: 0 24px; }

        /* --- Navigation --- */
        nav { position: fixed; top: 0; width: 100%; z-index: 1000; padding: 8px 0; animation: navDrop 0.8s ease-out both; }
        .nav-inner {
            display: flex; justify-content: space-between; align-items: center;
            background: rgba(255, 255, 255, 0.8); backdrop-filter: blur(32px);
            padding: 6px 24px; border-radius: 20px;
            border: 1px solid rgba(255, 255, 255, 0.4);
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.05);
            animation: floatGentle 7s ease-in-out infinite;
        }
        .logo { display: flex; align-items: center; gap: 12px; text-decoration: none; font-weight: 800; font-size: 1.4rem; color: var(--slate-900); font-family: 'Manrope', sans-serif; }
        .nav-links { display: flex; gap: 40px; list-style: none; }
        .nav-links a { text-decoration: none; color: var(--slate-900); font-weight: 700; font-size: 0.9rem; opacity: 0.6; transition: var(--transition); }
        .nav-links a:hover, .nav-links a.active { opacity: 1; color: var(--aqua-600); }
        .nav-cta {
            background: var(--aqua-600); color: white; padding: 14px 28px; border-radius: 16px;
            text-decoration: none; font-weight: 800; font-size: 0.85rem; transition: var(--transition);
            display: flex; align-items: center; gap: 10px; box-shadow: 0 8px 20px rgba(2, 132, 199, 0.2);
        }
        .nav-cta:hover { transform: translateY(-2px); background: var(--slate-900); }

        /* --- Footer --- */
        footer { padding: 100px 0 40px; background: #fdfdfd; border-top: 1px solid var(--aqua-50); margin-top: 100px; }
        .footer-grid { display: grid; grid-template-columns: 1.5fr 1fr 1fr 1fr; gap: 60px; margin-bottom: 80px; }
        .footer-col h4 { font-weight: 800; font-size: 1rem; margin-bottom: 24px; color: var(--slate-900); }
        .footer-col ul { list-style: none; }
        .footer-col li { margin-bottom: 12px; }
        .footer-col a { text-decoration: none; color: var(--slate-700); font-weight: 600; font-size: 0.9rem; transition: var(--transition); }
        .footer-col a:hover { color: var(--aqua-600); }
        .footer-bottom { display: flex; justify-content: space-between; align-items: center; padding-top: 40px; border-top: 1px solid var(--aqua-50); color: #94a3b8; font-size: 0.85rem; font-weight: 600; }

        @keyframes pageIn {
            from { opacity: 0; transform: translateY(12px); }
            to { opacity: 1; transform: translateY(0); }
        }

        @keyframes navDrop {
            from { opacity: 0; transform: translateY(-18px); }
            to { opacity: 1; transform: translateY(0); }
        }

        @keyframes drift {
            0%, 100% { transform: translate3d(0, 0, 0) scale(1); }
            50% { transform: translate3d(1.25rem, 1rem, 0) scale(1.05); }
        }

        @keyframes floatGentle {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-4px); }
        }

        @media (max-width: 1024px) {
            nav { padding: 20px 0; }
            .nav-inner { padding: 12px 20px; border-radius: 20px; }
            .nav-links { display: none; }
            main { padding-top: 120px; }
            .footer-grid { grid-template-columns: 1.5fr 1fr; gap: 48px; }
        }
        @media (max-width: 768px) {
            nav { padding: 14px 0; }
            .container { padding: 0 16px; }
            .nav-inner { padding: 10px 14px; border-radius: 18px; }
            .logo { font-size: 1rem; gap: 10px; }
            .logo img { width: 32px !important; height: 32px !important; }
            .nav-cta { padding: 10px 14px; border-radius: 14px; }
            main { padding-top: 108px; }
            .footer-grid { grid-template-columns: 1fr; text-align: center; }
            .footer-col { display: flex; flex-direction: column; align-items: center; }
            .footer-bottom { flex-direction: column; gap: 20px; text-align: center; }
        }
        @media (max-width: 480px) {
            .nav-inner { gap: 12px; }
            .nav-cta span { display: none; }
            .nav-cta { padding: 12px 18px; }
            .logo { font-size: 1.1rem; }
            .logo img { width: 32px; height: 32px; }
        }

        @stack('styles')
    </style>
</head>
<body>

    <nav>
        <div class="container">
            <div class="nav-inner">
                <a href="{{ route('home') }}" class="logo">
                    <img src="{{ asset('logo.png') }}" alt="Aqua Heart Logo" style="width: 36px; height: 36px; object-fit: contain; border-radius: 10px; box-shadow: 0 4px 12px rgba(0,0,0,0.1);">
                    Aqua Heart
                </a>

                <ul class="nav-links">
                    <li><a href="{{ route('story') }}" class="{{ request()->routeIs('story') ? 'active' : '' }}">Story</a></li>
                    <li><a href="{{ route('delivery') }}" class="{{ request()->routeIs('delivery') ? 'active' : '' }}">Delivery</a></li>
                    <li><a href="{{ route('contact') }}" class="{{ request()->routeIs('contact') ? 'active' : '' }}">Contact</a></li>
                </ul>

                <a href="tel:09277377521" class="nav-cta">
                    <i data-lucide="phone" size="18"></i>
                    <span>Call to Order</span>
                </a>
            </div>
        </div>
    </nav>

    <main>
        @yield('content')
    </main>

    <footer>
        <div class="container">
            <div class="footer-grid">
                <div class="footer-col">
                    <a href="{{ route('home') }}" class="logo" style="margin-bottom: 30px;">
                        <img src="{{ asset('logo.png') }}" alt="Aqua Heart Logo" style="width: 32px; height: 32px; object-fit: contain; border-radius: 8px; margin-right: 8px;">
                        Aqua Heart
                    </a>
                    <p style="font-size: 0.9rem; color: var(--slate-700);">Aqua Heart Purified Drinking Water. Guaranteed safe and clean from pure heart within.</p>
                </div>
                <div class="footer-col">
                    <h4>Company</h4>
                    <ul>
                        <li><a href="{{ route('story') }}">Our Story</a></li>
                        <li><a href="{{ route('story') }}#process">Process</a></li>
                        <li><a href="{{ route('story') }}#sustainability">Sustainability</a></li>
                    </ul>
                </div>
                <div class="footer-col">
                    <h4>Service</h4>
                    <ul>
                        <li><a href="{{ route('delivery') }}#home-refills">Home Refills</a></li>
                        <li><a href="{{ route('delivery') }}#office-delivery">Office Delivery</a></li>
                        <li><a href="{{ route('delivery') }}#bulk-orders">Bulk Orders</a></li>
                    </ul>
                </div>
                <div class="footer-col">
                    <h4>Resources</h4>
                    <ul>
                        <li><a href="{{ route('resources') }}#quality-report">Quality Report</a></li>
                        <li><a href="{{ route('resources') }}#help-center">Help Center</a></li>
                        <li><a href="{{ route('resources') }}#terms-of-use">Terms of Use</a></li>
                    </ul>
                </div>
            </div>

            <div class="footer-bottom">
                &copy; {{ date('Y') }} Aqua Heart Water Systems. All rights reserved.
                <div style="display: flex; gap: 24px;">
                    <span>Privacy Policy</span>
                    <span>Security</span>
                </div>
            </div>
        </div>
    </footer>

    <script>
        lucide.createIcons();

        function reveal() {
            var reveals = document.querySelectorAll(".reveal");
            for (var i = 0; i < reveals.length; i++) {
                var windowHeight = window.innerHeight;
                var elementTop = reveals[i].getBoundingClientRect().top;
                var elementVisible = 150;
                if (elementTop < windowHeight - elementVisible) {
                    reveals[i].classList.add("active");
                }
            }
        }

        window.addEventListener("scroll", reveal);
        window.addEventListener("load", reveal);
    </script>
    @stack('scripts')
</body>
</html>
