@extends('layouts.public')

@section('title', 'Aqua Heart Purified Drinking Water')

@push('styles')
<style>
    /* --- Hero Section --- */
    .hero {
        position: relative;
        min-height: 100vh;
        display: flex;
        flex-direction: column;
        justify-content: flex-start;
        align-items: flex-start;
        padding-top: 180px;
        padding-bottom: 120px;
        background: linear-gradient(135deg, var(--aqua-50) 0%, #ffffff 100%);
        overflow: hidden;
    }

    .hero::before {
        content: '';
        position: absolute;
        width: 800px;
        height: 800px;
        background: radial-gradient(circle, rgba(56, 189, 248, 0.08) 0%, transparent 70%);
        top: -200px;
        right: -200px;
        border-radius: 50%;
    }

    .hero-inner {
        display: grid;
        grid-template-columns: 1.1fr 0.9fr;
        align-items: center;
        gap: 80px;
        position: relative;
        z-index: 10;
    }

    .hero-content {
        padding-top: 72px;
    }

    .hero-badge {
        display: inline-flex;
        align-items: center;
        gap: 10px;
        background: white;
        padding: 8px 18px;
        border-radius: 999px;
        font-weight: 800;
        font-size: 0.75rem;
        color: var(--aqua-600);
        text-transform: uppercase;
        letter-spacing: 0.1em;
        box-shadow: 0 4px 10px rgba(0,0,0,0.02);
        margin-bottom: 24px;
        border: 1px solid var(--aqua-100);
    }

    .hero h1 {
        font-family: 'Manrope', sans-serif;
        font-size: clamp(3rem, 6vw, 5.2rem);
        line-height: 0.92;
        letter-spacing: -0.05em;
        margin-bottom: 32px;
        color: var(--slate-900);
    }

    .hero h1 span { color: var(--aqua-600); }

    .hero p {
        font-size: 1.15rem;
        color: var(--slate-700);
        line-height: 1.7;
        margin-bottom: 48px;
        max-width: 540px;
    }

    .hero-btns { display: flex; gap: 20px; }

    .btn {
        display: inline-flex;
        align-items: center;
        gap: 12px;
        padding: 18px 36px;
        border-radius: 20px;
        font-weight: 800;
        text-decoration: none;
        transition: var(--transition);
    }

    .btn-primary {
        background: var(--slate-900);
        color: white;
        box-shadow: 0 20px 40px rgba(15, 23, 42, 0.15);
    }

    .btn-primary:hover {
        transform: translateY(-4px);
        background: var(--aqua-600);
        box-shadow: 0 30px 50px rgba(2, 132, 199, 0.2);
    }

    .btn-secondary {
        background: white;
        color: var(--slate-900);
        border: 2px solid var(--aqua-100);
    }

    .btn-secondary:hover {
        border-color: var(--aqua-400);
        transform: translateY(-4px);
    }

    /* --- Featured Image --- */
    .hero-media {
        position: relative;
        padding-top: 24px;
        margin-top: 120px;
    }
    .main-img-wrap {
        position: relative;
        z-index: 5;
        border-radius: 40px;
        overflow: hidden;
        box-shadow: 0 50px 100px rgba(0,0,0,0.1);
        transform: rotate(-2deg);
    }
    .main-img { width: 100%; display: block; }
    .cert-card {
        position: absolute;
        bottom: 20px;
        left: -20px;
        background: white;
        padding: 24px;
        border-radius: 24px;
        box-shadow: 0 20px 50px rgba(0,0,0,0.08);
        z-index: 10;
        display: flex;
        align-items: center;
        gap: 16px;
        border: 1px solid var(--aqua-50);
    }
    .lottie-box {
        width: 50px;
        height: 50px;
        background: var(--aqua-50);
        border-radius: 14px;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    .cert-info h4 { font-weight: 800; font-size: 1rem; color: var(--slate-900); }
    .cert-info p { font-size: 0.75rem; color: var(--slate-700); font-weight: 600; opacity: 0.8; }

    /* --- Sections --- */
    .section { padding: 140px 0; }
    .section-header { text-align: center; max-width: 700px; margin: 0 auto 80px; }
    .section-header h2 { font-family: 'Manrope', sans-serif; font-size: 3rem; letter-spacing: -0.04em; margin-bottom: 24px; }

    .feature-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 32px; }
    .feature-card { background: var(--aqua-50); padding: 48px; border-radius: 32px; transition: var(--transition); border: 1px solid transparent; }
    .feature-card:hover { background: white; border-color: var(--aqua-100); box-shadow: 0 30px 60px rgba(0,0,0,0.04); transform: translateY(-10px); }
    .feature-icon { width: 64px; height: 64px; background: white; color: var(--aqua-600); border-radius: 18px; display: flex; align-items: center; justify-content: center; margin-bottom: 32px; box-shadow: 0 10px 20px rgba(0,0,0,0.02); }
    .feature-card h3 { font-size: 1.5rem; font-weight: 800; margin-bottom: 16px; }
    .feature-card p { color: var(--slate-700); font-size: 1rem; }

    /* --- Testimonials --- */
    .testimonial-bar {
        background: var(--slate-900); border-radius: var(--radius-xl); padding: 80px; color: white; display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 60px;
    }
    .t-item blockquote { font-size: 1.25rem; font-weight: 600; line-height: 1.6; margin-bottom: 24px; font-style: italic; opacity: 0.9; }
    .t-user { display: flex; align-items: center; gap: 12px; }
    .t-avatar { width: 40px; height: 40px; background: var(--aqua-600); border-radius: 10px; }
    .t-user span { font-weight: 800; font-size: 0.9rem; }

    @keyframes float { 0%, 100% { transform: translateY(0) rotate(-2deg); } 50% { transform: translateY(-15px) rotate(0deg); } }
    @keyframes pulse-soft { 0%, 100% { opacity: 0.8; transform: scale(1); } 50% { opacity: 1; transform: scale(1.05); } }
    .floating { animation: float 6s ease-in-out infinite; }

    @media (max-width: 1024px) {
        .hero { padding-top: 140px; padding-bottom: 90px; }
        .hero-inner { grid-template-columns: 1fr; text-align: center; gap: 60px; }
        .hero-content { display: flex; flex-direction: column; align-items: center; padding-top: 0; }
        .hero h1 { font-size: clamp(2.5rem, 8vw, 4rem); }
        .hero p { margin-inline: auto; font-size: 1.05rem; }
        .hero-media { display: flex; justify-content: center; width: 100%; max-width: 500px; margin: 0 auto; padding-top: 0; }
        .cert-card { left: 50%; transform: translateX(-50%); bottom: -20px; width: max-content; }
        .section { padding: 80px 0; }
        .section-header h2 { font-size: 2.2rem; }
        .feature-grid { grid-template-columns: 1fr; gap: 24px; }
        .testimonial-bar { grid-template-columns: 1fr; padding: 40px 24px; gap: 40px; }
    }

    @media (max-width: 768px) {
        .hero {
            min-height: auto;
            padding-top: 104px;
            padding-bottom: 72px;
        }

        .hero-inner {
            gap: 36px;
        }

        .hero-badge {
            font-size: 0.68rem;
            padding: 8px 14px;
            text-align: left;
        }

        .hero h1 {
            font-size: 2.7rem;
            line-height: 0.98;
            margin-bottom: 20px;
        }

        .hero p {
            font-size: 0.98rem;
            line-height: 1.65;
            margin-bottom: 30px;
        }

        .hero-btns {
            flex-direction: column;
            width: 100%;
        }

        .btn {
            width: 100%;
            justify-content: center;
            padding: 16px 20px;
            border-radius: 16px;
        }

        .hero-media {
            max-width: 100%;
        }

        .main-img-wrap {
            border-radius: 26px;
            transform: none;
        }

        .cert-card {
            position: static;
            transform: none;
            width: 100%;
            margin-top: 18px;
            justify-content: center;
            text-align: left;
        }

        .section {
            padding: 64px 0;
        }

        .section-header {
            margin-bottom: 40px;
        }

        .section-header h2 {
            font-size: 2rem;
        }

        .feature-card {
            padding: 28px 22px;
            border-radius: 24px;
        }

        .testimonial-bar {
            border-radius: 24px;
            padding: 28px 20px;
            gap: 28px;
        }
    }

    @media (max-width: 480px) {
        .hero { padding-top: 100px; min-height: auto; padding-bottom: 80px; }
        .hero h1 { font-size: 2.2rem; }
        .hero p { font-size: 0.94rem; }
        .hero-media { padding-top: 0; margin-top: 0; }
        .cert-card { padding: 18px; border-radius: 18px; }
    }
</style>
@endpush

@section('content')
<!-- Elite Hero -->
<section class="hero">
    <div class="container">
        <div class="hero-inner">
            <div class="hero-content">
                <div class="hero-badge">
                    <i data-lucide="star" size="14" fill="currentColor"></i>
                    Aqua Heart Purified Drinking Water
                </div>
                <h1>Aqua Heart <span>Purified Drinking Water</span></h1>
                <p>"Guaranteed Safe and Clean from Pure Heart Within"</p>
                
                <div class="hero-btns">
                    <a href="{{ route('contact') }}" class="btn btn-primary">
                        Contact Us
                        <i data-lucide="arrow-right" size="18"></i>
                    </a>
                    <a href="{{ route('delivery') }}" class="btn btn-secondary">View Delivery</a>
                </div>
            </div>

            <div class="hero-media reveal floating">
                <div class="main-img-wrap">
                    <img src="{{ asset('aqua_heart_hero_premium.png') }}" alt="Premium Hydration" class="main-img">
                </div>
                <div class="cert-card" style="animation: pulse-soft 3s infinite ease-in-out;">
                    <div class="lottie-box">
                        <dotlottie-player 
                            src="{{ asset('lottie/success.lottie') }}" 
                            background="transparent" 
                            speed="1" 
                            style="width: 40px; height: 40px;" 
                            autoplay>
                        </dotlottie-player>
                    </div>
                    <div class="cert-info">
                        <h4>Trusted Service</h4>
                        <p>Available for delivery and refill inquiries</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Features -->
<section class="section reveal" id="about">
    <div class="container">
        <div class="section-header">
            <h2>Built for <span>Pure Hydration</span></h2>
            <p>Aqua Heart focuses on safe, clean drinking water for homes and businesses in Mapolopolo, Basista, Pangasinan.</p>
        </div>

        <div class="feature-grid">
            <div class="feature-card">
                <div class="feature-icon"><i data-lucide="zap"></i></div>
                <h3>Clean Water Supply</h3>
                <p>Reliable purified drinking water prepared for everyday household and business use.</p>
            </div>
            <div class="feature-card">
                <div class="feature-icon"><i data-lucide="heart"></i></div>
                <h3>Safe and Fresh</h3>
                <p>Every refill is handled with care to keep the water clean and ready for consumption.</p>
            </div>
            <div class="feature-card">
                <div class="feature-icon"><i data-lucide="truck"></i></div>
                <h3>Easy Ordering</h3>
                <p>Call or message the station to arrange delivery or ask about refill availability.</p>
            </div>
        </div>
    </div>
</section>

<!-- Testimonials -->
<section class="section reveal" style="padding-top: 0;">
    <div class="container">
        <div class="testimonial-bar">
            <div class="t-item">
                <blockquote>"Clean water and good service make Aqua Heart easy to recommend."</blockquote>
                <div class="t-user">
                    <div class="t-avatar"></div>
                    <span>Angela Santiago</span>
                </div>
            </div>
            <div class="t-item">
                <blockquote>"Their team is responsive and the water is always fresh."</blockquote>
                <div class="t-user">
                    <div class="t-avatar"></div>
                    <span>Mark Jayson</span>
                </div>
            </div>
            <div class="t-item">
                <blockquote>"Aqua Heart is our go-to for purified drinking water at home."</blockquote>
                <div class="t-user">
                    <div class="t-avatar"></div>
                    <span>Sophia Rivera</span>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Contact CTA -->
<section class="section reveal" id="contact" style="text-align: center;">
    <div class="container">
        <div style="background: var(--aqua-50); padding: 100px 40px; border-radius: 60px;">
            <h2 style="font-size: clamp(2rem, 5vw, 4rem); font-family: 'Manrope', sans-serif; margin-bottom: 24px;">Aqua Heart Purified Drinking Water</h2>
            <p style="font-size: 1.2rem; color: var(--slate-700); margin-bottom: 48px; max-width: 600px; margin-left: auto; margin-right: auto;">"Guaranteed Safe and Clean from Pure Heart Within"</p>
            
            <a href="tel:09277377521" class="btn btn-primary" style="padding: 24px 60px; font-size: 1.2rem;">
                <i data-lucide="phone-call"></i>
                09277377521
            </a>
        </div>
    </div>
</section>
@endsection
