<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Aqua Heart | Pure Hydration, Redefined</title>
    <meta name="description" content="Premium purified water delivery and refill station. Certified, refreshing, and delivered with care. Aqua Heart — Purely for Your Heart.">
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Syne:wght@400;700;800&family=Outfit:wght@300;400;600;700&display=swap" rel="stylesheet">
    
    <!-- Icons -->
    <script src="https://unpkg.com/lucide@latest"></script>
    
    <!-- GSAP for animations (CDN) -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/gsap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/ScrollTrigger.min.js"></script>

    <style>
        :root {
            --primary: #0ea5e9;
            --primary-dark: #0369a1;
            --secondary: #06b6d4;
            --accent: #22d3ee;
            --dark: #0f172a;
            --light: #f8fafc;
            --white: #ffffff;
            --glass: rgba(255, 255, 255, 0.7);
            --transition: all 0.5s cubic-bezier(0.19, 1, 0.22, 1);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Outfit', sans-serif;
            background-color: var(--light);
            color: var(--dark);
            overflow-x: hidden;
            line-height: 1.6;
        }

        h1, h2, h3, h4, .syne {
            font-family: 'Syne', sans-serif;
            text-transform: uppercase;
            letter-spacing: -0.02em;
        }

        /* --- Smooth Scroll Fix --- */
        html {
            scroll-behavior: smooth;
        }

        /* --- Navigation --- */
        nav {
            position: fixed;
            top: 0;
            width: 100%;
            z-index: 1000;
            padding: 24px 0;
            transition: var(--transition);
        }

        nav.scrolled {
            padding: 16px 0;
            background: rgba(255, 255, 255, 0.8);
            backdrop-filter: blur(20px);
            box-shadow: 0 4px 30px rgba(0, 0, 0, 0.03);
            border-bottom: 1px solid rgba(255, 255, 255, 0.3);
        }

        .container {
            max-width: 1300px;
            margin: 0 auto;
            padding: 0 24px;
        }

        .nav-inner {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .logo {
            display: flex;
            align-items: center;
            gap: 12px;
            text-decoration: none;
            font-weight: 800;
            font-size: 1.5rem;
            color: var(--dark);
        }

        .logo-icon {
            width: 40px;
            height: 40px;
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            box-shadow: 0 10px 20px rgba(14, 165, 233, 0.2);
        }

        .nav-links {
            display: flex;
            gap: 40px;
            list-style: none;
        }

        .nav-links a {
            text-decoration: none;
            color: var(--dark);
            font-weight: 600;
            font-size: 0.9rem;
            opacity: 0.7;
            transition: var(--transition);
            position: relative;
        }

        .nav-links a::after {
            content: '';
            position: absolute;
            bottom: -6px;
            left: 0;
            width: 0;
            height: 2px;
            background: var(--primary);
            transition: var(--transition);
        }

        .nav-links a:hover {
            opacity: 1;
        }

        .nav-links a:hover::after {
            width: 100%;
        }

        .nav-cta {
            background: var(--dark);
            color: white;
            padding: 14px 28px;
            border-radius: 100px;
            text-decoration: none;
            font-weight: 700;
            font-size: 0.9rem;
            transition: var(--transition);
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .nav-cta:hover {
            background: var(--primary);
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(14, 165, 233, 0.3);
        }

        /* --- Hero Section --- */
        .hero {
            position: relative;
            min-height: 100vh;
            display: flex;
            align-items: center;
            padding-top: 100px;
            overflow: hidden;
            background: radial-gradient(circle at top right, rgba(14, 165, 233, 0.05) 0%, transparent 50%);
        }

        .hero-inner {
            display: grid;
            grid-template-columns: 1.1fr 0.9fr;
            align-items: center;
            gap: 60px;
        }

        .hero-tagline {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            background: rgba(14, 165, 233, 0.1);
            color: var(--primary-dark);
            padding: 8px 16px;
            border-radius: 100px;
            font-weight: 700;
            font-size: 0.8rem;
            margin-bottom: 24px;
            text-transform: uppercase;
            letter-spacing: 0.1em;
        }

        .hero h1 {
            font-size: clamp(3rem, 8vw, 5.5rem);
            line-height: 0.95;
            font-weight: 800;
            margin-bottom: 32px;
        }

        .hero h1 span {
            color: var(--primary);
            position: relative;
        }

        .hero p {
            font-size: 1.2rem;
            color: #64748b;
            margin-bottom: 48px;
            max-width: 550px;
        }

        .hero-btns {
            display: flex;
            gap: 20px;
        }

        .btn {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            padding: 18px 36px;
            border-radius: 100px;
            font-weight: 700;
            text-decoration: none;
            transition: var(--transition);
        }

        .btn-primary {
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            color: white;
            box-shadow: 0 20px 40px rgba(14, 165, 233, 0.2);
        }

        .btn-primary:hover {
            transform: translateY(-4px);
            box-shadow: 0 30px 50px rgba(14, 165, 233, 0.3);
        }

        .btn-outline {
            border: 2px solid var(--dark);
            color: var(--dark);
        }

        .btn-outline:hover {
            background: var(--dark);
            color: white;
            transform: translateY(-4px);
        }

        .hero-img-container {
            position: relative;
        }

        .hero-img-bg {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 140%;
            height: 140%;
            background: radial-gradient(circle, rgba(14, 165, 233, 0.1) 0%, transparent 70%);
            z-index: -1;
            border-radius: 50%;
        }

        .hero-img {
            width: 100%;
            border-radius: 40px;
            box-shadow: 0 50px 100px rgba(0,0,0,0.1);
        }

        .floating-stat {
            position: absolute;
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(10px);
            padding: 20px;
            border-radius: 20px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.05);
            display: flex;
            align-items: center;
            gap: 15px;
            z-index: 10;
            border: 1px solid rgba(255,255,255,0.5);
        }

        .stat-1 { top: 10%; right: -10%; }
        .stat-2 { bottom: 20%; left: -15%; }

        @keyframes float {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-20px); }
        }

        .floating {
            animation: float 6s infinite ease-in-out;
        }

        /* --- Sections General --- */
        .section {
            padding: 140px 0;
            position: relative;
        }

        .section-center {
            text-align: center;
            max-width: 800px;
            margin: 0 auto 80px;
        }

        .tag {
            color: var(--primary);
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 0.2em;
            font-size: 0.9rem;
            margin-bottom: 16px;
            display: block;
        }

        .section h2 {
            font-size: clamp(2rem, 5vw, 3.5rem);
            line-height: 1;
            margin-bottom: 24px;
        }

        /* --- Services --- */
        .services-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 30px;
        }

        .service-card {
            background: white;
            padding: 48px;
            border-radius: 32px;
            transition: var(--transition);
            border: 1px solid rgba(0,0,0,0.02);
            position: relative;
            overflow: hidden;
        }

        .service-card:hover {
            transform: translateY(-12px);
            box-shadow: 0 40px 80px rgba(0,0,0,0.05);
            border-color: var(--primary);
        }

        .service-icon {
            width: 70px;
            height: 70px;
            background: #eff6ff;
            border-radius: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--primary);
            margin-bottom: 32px;
            transition: var(--transition);
        }

        .service-card:hover .service-icon {
            background: var(--primary);
            color: white;
            transform: scale(1.1);
        }

        .service-card h3 {
            font-size: 1.7rem;
            margin-bottom: 16px;
        }

        .service-card p {
            color: #64748b;
            font-size: 1.05rem;
        }

        /* --- Process --- */
        .process-split {
            display: grid;
            grid-template-columns: 1fr 1fr;
            align-items: center;
            gap: 80px;
        }

        .process-list {
            list-style: none;
            margin-top: 40px;
        }

        .process-item {
            display: flex;
            gap: 24px;
            margin-bottom: 40px;
            opacity: 0;
            transform: translateX(30px);
        }

        .process-num {
            width: 60px;
            height: 60px;
            background: var(--dark);
            color: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 800;
            font-size: 1.2rem;
            flex-shrink: 0;
            font-family: 'Syne', sans-serif;
            border: 4px solid var(--light);
            box-shadow: 0 0 0 4px var(--primary);
        }

        .process-item h4 {
            font-size: 1.4rem;
            margin-bottom: 8px;
        }

        .process-item p {
            color: #64748b;
        }

        /* --- Testimonials --- */
        .testimonials {
            background: #0f172a;
            color: white;
            border-radius: 60px;
            padding: 100px 40px;
            margin: 0 20px;
            overflow: hidden;
        }

        .t-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 40px;
        }

        .t-card {
            padding: 40px;
            background: rgba(255,255,255,0.05);
            border-radius: 32px;
            border: 1px solid rgba(255,255,255,0.1);
            transition: var(--transition);
        }

        .t-card:hover {
            background: rgba(255,255,255,0.1);
            transform: scale(1.02);
        }

        .t-quote {
            font-size: 1.2rem;
            margin-bottom: 32px;
            font-style: italic;
            opacity: 0.9;
        }

        .t-author {
            display: flex;
            align-items: center;
            gap: 16px;
        }

        .t-avatar {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--primary), var(--secondary));
        }

        /* --- Footer --- */
        footer {
            padding: 120px 0 60px;
            background: white;
        }

        .footer-grid {
            display: grid;
            grid-template-columns: 1.5fr 1fr 1fr 1fr;
            gap: 60px;
            margin-bottom: 100px;
        }

        .footer-logo h2 {
            font-size: 2rem;
            margin-bottom: 24px;
        }

        .footer-links h4 {
            font-size: 1.1rem;
            margin-bottom: 24px;
            color: var(--dark);
        }

        .footer-links ul {
            list-style: none;
        }

        .footer-links li {
            margin-bottom: 12px;
        }

        .footer-links a {
            text-decoration: none;
            color: #64748b;
            transition: var(--transition);
        }

        .footer-links a:hover {
            color: var(--primary);
            padding-left: 5px;
        }

        .footer-bottom {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding-top: 40px;
            border-top: 1px solid #f1f5f9;
            color: #94a3b8;
            font-size: 0.9rem;
        }

        .social {
            display: flex;
            gap: 20px;
        }

        .social-link {
            width: 40px;
            height: 40px;
            background: #f1f5f9;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--dark);
            transition: var(--transition);
            text-decoration: none;
        }

        .social-link:hover {
            background: var(--primary);
            color: white;
            transform: translateY(-5px);
        }

        /* --- Animations & Responsive --- */
        @media (max-width: 1024px) {
            .hero-inner, .process-split {
                grid-template-columns: 1fr;
                text-align: center;
            }
            .hero h1 { font-size: 4rem; }
            .hero p { margin: 0 auto 48px; }
            .hero-btns { justify-content: center; }
            .hero-img-container { max-width: 600px; margin: 60px auto 0; }
            .services-grid, .t-grid, .footer-grid { grid-template-columns: 1fr; }
            .process-item { text-align: left; }
        }

        /* Special Animated Background */
        .wave-bg {
            position: absolute;
            bottom: 0;
            left: 0;
            width: 100%;
            height: 15vh;
            background: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 1440 320'%3E%3Cpath fill='%230ea5e9' fill-opacity='0.05' d='M0,192L48,197.3C96,203,192,213,288,229.3C384,245,480,267,576,250.7C672,235,768,181,864,181.3C960,181,1056,235,1152,234.7C1248,235,1344,181,1392,154.7L1440,128L1440,320L1392,320C1344,320,1248,320,1152,320C1056,320,960,320,864,320C768,320,672,320,576,320C480,320,384,320,288,320C192,320,96,320,48,320L0,320Z'%3E%3C/path%3E%3C/svg%3E");
            background-size: cover;
            animation: waveMove 20s linear infinite;
        }

        @keyframes waveMove {
            0% { background-position-x: 0; }
            100% { background-position-x: 1440px; }
        }

        /* Reveal Animation Classes */
        .reveal {
            opacity: 0;
            transform: translateY(30px);
            transition: all 0.8s ease-out;
        }

        .reveal.active {
            opacity: 1;
            transform: translateY(0);
        }

    </style>
</head>
<body>

    <nav id="navbar">
        <div class="container nav-inner">
            <a href="/" class="logo">
                <div class="logo-icon">
                    <i data-lucide="droplet"></i>
                </div>
                Aqua Heart
            </a>
            
            <ul class="nav-links">
                <li><a href="#about">Our Story</a></li>
                <li><a href="#services">Services</a></li>
                <li><a href="#process">Quality Control</a></li>
                <li><a href="#contact">Contact</a></li>
            </ul>

            <a href="/login" class="nav-cta">
                <i data-lucide="user" size="18"></i>
                Admin Portal
            </a>
        </div>
    </nav>

    <main>
        <!-- Hero Section -->
        <section class="hero">
            <div class="container hero-inner">
                <div class="hero-content">
                    <div class="hero-tagline">
                        <i data-lucide="shield-check" size="16"></i>
                        WHO & DOH Certified Station
                    </div>
                    <h1>Purest <span>Water</span> For Purest <span>Hearts</span></h1>
                    <p>Elevate your family's hydration with crystal-clear, mineral-rich purified water. Delivered fresh to your doorstep in minutes.</p>
                    
                    <div class="hero-btns">
                        <a href="#contact" class="btn btn-primary">
                            Order Refill Now
                            <i data-lucide="arrow-right" size="20"></i>
                        </a>
                        <a href="#services" class="btn btn-outline">Explore More</a>
                    </div>
                </div>
                
                <div class="hero-img-container">
                    <div class="hero-img-bg"></div>
                    <!-- High Quality Product Image Placeholder -->
                    <img src="https://images.unsplash.com/photo-1523362628745-0c100150b504?q=80&w=1000&auto=format&fit=crop" alt="Fresh Water" class="hero-img">
                    
                    <div class="floating-stat stat-1 floating">
                        <div style="color: var(--primary); background: #f0f9ff; p: 10px; border-radius: 12px;">
                            <i data-lucide="zap"></i>
                        </div>
                        <div>
                            <p style="font-weight: 800; font-size: 1.1rem;">30 Min</p>
                            <p style="font-size: 0.8rem; color: #64748b;">Fastest Delivery</p>
                        </div>
                    </div>

                    <div class="floating-stat stat-2 floating" style="animation-delay: -3s;">
                        <div style="color: #10b981; background: #f0fdf4; p: 10px; border-radius: 12px;">
                            <i data-lucide="check-circle"></i>
                        </div>
                        <div>
                            <p style="font-weight: 800; font-size: 1.1rem;">0% Impurities</p>
                            <p style="font-size: 0.8rem; color: #64748b;">Laboratory Tested</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="wave-bg"></div>
        </section>

        <!-- About / Counter Section -->
        <section id="about" class="section">
            <div class="container">
                <div class="section-center reveal">
                    <span class="tag">Trusted by Families</span>
                    <h2>Fueling the Health of Our Local Community</h2>
                    <p style="font-size: 1.2rem; color: #64748b;">Since 2026, Aqua Heart has been the gold standard for drinking water. We don't just filter; we revitalize every drop with essential minerals.</p>
                </div>

                <div class="services-grid">
                    <div class="service-card reveal">
                        <div class="service-icon"><i data-lucide="truck"></i></div>
                        <h3>Doorstep Delivery</h3>
                        <p>No more heavy lifting. Our specialized fleet brings fresh water straight to your kitchen or office on schedule.</p>
                    </div>
                    <div class="service-card reveal">
                        <div class="service-icon"><i data-lucide="factory"></i></div>
                        <h3>Modern Station</h3>
                        <p>Visit our walk-in station featuring the latest automated nozzle cleaning and sterilized refilling systems.</p>
                    </div>
                    <div class="service-card reveal">
                        <div class="service-icon"><i data-lucide="award"></i></div>
                        <h3>Bulk Contracts</h3>
                        <p>Reliable water solutions for schools, hospitals, and restaurants with flexible billing and priority service.</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- Process Section -->
        <section id="process" class="section" style="background-color: #f1f5f9;">
            <div class="container process-split">
                <div class="reveal">
                    <span class="tag">Our Tech Stack</span>
                    <h2>The 24-Stage Gold Standard</h2>
                    <p style="color: #64748b; margin-bottom: 40px;">Quality isn't accidental. It's the result of rigorous engineering and constant monitoring.</p>
                    
                    <div class="process-list">
                        <div class="process-item reveal">
                            <div class="process-num">01</div>
                            <div>
                                <h4>Molecular Filtration</h4>
                                <p>Removing bacteria, viruses, and chemicals at the microscopic level through Reverse Osmosis.</p>
                            </div>
                        </div>
                        <div class="process-item reveal">
                            <div class="process-num">02</div>
                            <div>
                                <h4>Mineral Infusion</h4>
                                <p>We re-introduce balanced electrolytes to ensure every sip is as healthy as it is refreshing.</p>
                            </div>
                        </div>
                        <div class="process-item reveal">
                            <div class="process-num">03</div>
                            <div>
                                <h4>UV Sterilization</h4>
                                <p>The final layer of safety using high-intensity UV rays to ensure zero microbial contamination.</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="hero-img-container reveal" style="transform: skewY(-2deg);">
                    <img src="https://images.unsplash.com/photo-1563223552-30d01fda3ea6?q=80&w=1000&auto=format&fit=crop" alt="Water Purification" class="hero-img">
                </div>
            </div>
        </section>

        <!-- Testimonials -->
        <section class="section">
            <div class="container">
                <div class="testimonials reveal">
                    <div class="section-center" style="margin-bottom: 60px;">
                        <span class="tag" style="color: var(--accent);">Real Experiences</span>
                        <h2 style="color: white;">Heartfelt Stories</h2>
                    </div>

                    <div class="t-grid">
                        <div class="t-card">
                            <p class="t-quote">"Aqua Heart is the only water my kids will drink. The difference in taste is actually unbelievable compared to others."</p>
                            <div class="t-author">
                                <div class="t-avatar"></div>
                                <div>
                                    <p style="font-weight: 800;">Sarah Jenkins</p>
                                    <p style="font-size: 0.8rem; opacity: 0.6;">Local Resident</p>
                                </div>
                            </div>
                        </div>
                        <div class="t-card">
                            <p class="t-quote">"We use them for our restaurant. The reliability and water clarity they provide is essential for our operations."</p>
                            <div class="t-author">
                                <div class="t-avatar"></div>
                                <div>
                                    <p style="font-weight: 800;">David Chen</p>
                                    <p style="font-size: 0.8rem; opacity: 0.6;">Business Owner</p>
                                </div>
                            </div>
                        </div>
                        <div class="t-card">
                            <p class="t-quote">"Fastest delivery in the district! Every time I call, they are here within 20 minutes. Exceptional service."</p>
                            <div class="t-author">
                                <div class="t-avatar"></div>
                                <div>
                                    <p style="font-weight: 800;">Mike Perez</p>
                                    <p style="font-size: 0.8rem; opacity: 0.6;">Regular Customer</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- CTA Section -->
        <section id="contact" class="section">
            <div class="container">
                <div class="reveal" style="background: linear-gradient(135deg, var(--primary-dark), var(--secondary)); border-radius: 60px; padding: 100px 40px; text-align: center; color: white;">
                    <h2 style="font-size: clamp(2.5rem, 6vw, 4.5rem); margin-bottom: 24px;">Ready for a Refresh?</h2>
                    <p style="font-size: 1.3rem; opacity: 0.9; max-width: 600px; margin: 0 auto 48px;">Join the 5,000+ happy families getting pure hydration everyday.</p>
                    
                    <div style="display: flex; gap: 20px; justify-content: center; flex-wrap: wrap;">
                        <a href="tel:+123456789" class="btn" style="background: white; color: var(--primary);">
                            <i data-lucide="phone"></i>
                            Call To Order
                        </a>
                        <a href="#" class="btn" style="background: rgba(255,255,255,0.1); color: white; border: 1px solid rgba(255,255,255,0.3);">
                            <i data-lucide="message-square"></i>
                            Chat on WhatsApp
                        </a>
                    </div>
                </div>
            </div>
        </section>
    </main>

    <footer>
        <div class="container">
            <div class="footer-grid">
                <div class="footer-logo">
                    <a href="/" class="logo" style="margin-bottom: 24px;">
                        <div class="logo-icon"><i data-lucide="droplet"></i></div>
                        Aqua Heart
                    </a>
                    <p style="color: #64748b; max-width: 300px;">The gold standard in purified water since 2026. Science-driven, heart-centered hydration.</p>
                </div>
                
                <div class="footer-links">
                    <h4>Company</h4>
                    <ul>
                        <li><a href="#about">About Us</a></li>
                        <li><a href="#services">Services</a></li>
                        <li><a href="#process">Process</a></li>
                        <li><a href="/admin/login">Admin Portal</a></li>
                    </ul>
                </div>

                <div class="footer-links">
                    <h4>Support</h4>
                    <ul>
                        <li><a href="#">Delivery Info</a></li>
                        <li><a href="#">Billing Policy</a></li>
                        <li><a href="#">Terms of Service</a></li>
                        <li><a href="#">Privacy</a></li>
                    </ul>
                </div>

                <div class="footer-links">
                    <h4>Socials</h4>
                    <div class="social">
                        <a href="#" class="social-link"><i data-lucide="facebook" size="18"></i></a>
                        <a href="#" class="social-link"><i data-lucide="instagram" size="18"></i></a>
                        <a href="#" class="social-link"><i data-lucide="twitter" size="18"></i></a>
                    </div>
                </div>
            </div>

            <div class="footer-bottom">
                <p>&copy; 2026 Aqua Heart Water Station. All rights reserved.</p>
                <p>Curated for Pure Hydration.</p>
            </div>
        </div>
    </footer>

    <script>
        // Init Lucide
        lucide.createIcons();

        // Scroll Effect
        window.addEventListener('scroll', () => {
            const nav = document.getElementById('navbar');
            if (window.scrollY > 50) {
                nav.classList.add('scrolled');
            } else {
                nav.classList.remove('scrolled');
            }
        });

        // Simple Intersection Observer for reveals
        const observerOptions = { threshold: 0.1 };
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('active');
                }
            });
        }, observerOptions);

        document.querySelectorAll('.reveal').forEach(el => observer.observe(el));

        // GSAP Animations for Hero
        gsap.from(".hero h1", { duration: 1.2, y: 50, opacity: 0, ease: "power4.out", delay: 0.2 });
        gsap.from(".hero p", { duration: 1.2, y: 30, opacity: 0, ease: "power4.out", delay: 0.4 });
        gsap.from(".hero-btns", { duration: 1.2, y: 20, opacity: 0, ease: "power4.out", delay: 0.6 });
        gsap.from(".hero-img-container", { duration: 1.5, x: 100, opacity: 0, ease: "power4.out", delay: 0.5 });
    </script>
</body>
</html>
