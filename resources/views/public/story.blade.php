@extends('layouts.public')

@section('title', 'Our Story')

@push('styles')
<style>
    .page-hero {
        padding: 72px 0 88px;
        background: linear-gradient(135deg, var(--aqua-50) 0%, #ffffff 100%);
        text-align: center;
    }
    .page-hero h1 { font-family: 'Manrope', sans-serif; font-size: clamp(2.5rem, 5vw, 4rem); letter-spacing: -0.05em; margin-bottom: 20px; }
    .page-hero p { max-width: 600px; margin: 0 auto; color: var(--slate-700); font-size: 1.1rem; }

    .story-section { padding: 100px 0; }
    .story-grid { display: grid; grid-template-columns: 1fr 1.2fr; gap: 80px; align-items: center; }
    
    .story-media { position: relative; border-radius: 40px; overflow: hidden; box-shadow: 0 40px 80px rgba(0,0,0,0.1); }
    .story-image { width: 100%; display: block; }
    
    .story-content h2 { font-family: 'Manrope', sans-serif; font-size: 2.5rem; margin-bottom: 24px; color: var(--slate-900); }
    .story-content p { font-size: 1.1rem; color: var(--slate-700); margin-bottom: 32px; line-height: 1.8; }

    .process-grid { display: grid; grid-template-columns: repeat(2, 1fr); gap: 30px; margin-top: 40px; }
    .process-card { background: var(--aqua-50); padding: 30px; border-radius: 24px; border: 1px solid var(--aqua-100); }
    .process-card h4 { font-weight: 800; margin-bottom: 10px; color: var(--aqua-600); }
    .process-card p { font-size: 0.9rem; margin-bottom: 0; }

    .story-mission {
        background: var(--slate-900);
        color: white;
    }

    .story-mission-inner {
        text-align: center;
    }

    .story-mission-inner h2 {
        font-family: 'Manrope', sans-serif;
        font-size: 2.5rem;
        margin-bottom: 24px;
    }

    .story-mission-inner > p {
        max-width: 800px;
        margin: 0 auto 60px;
        font-size: 1.25rem;
        opacity: 0.8;
    }

    @media (max-width: 1024px) {
        .page-hero { padding: 56px 0 72px; }
        .story-section { padding: 72px 0; }
        .story-grid { grid-template-columns: 1fr; text-align: center; }
        .process-grid { grid-template-columns: 1fr; }
    }

    @media (max-width: 768px) {
        .page-hero {
            padding: 40px 0 56px;
        }

        .page-hero p {
            font-size: 0.98rem;
        }

        .story-section {
            padding: 56px 0;
        }

        .story-grid {
            gap: 32px;
        }

        .story-media {
            border-radius: 24px;
        }

        .story-content h2 {
            font-size: 2rem;
            margin-bottom: 16px;
        }

        .story-content p {
            font-size: 0.98rem;
            line-height: 1.7;
            margin-bottom: 22px;
        }

        .process-card {
            padding: 22px 18px;
            border-radius: 20px;
        }

        .story-mission-inner h2 {
            font-size: 2rem;
        }

        .story-mission-inner > p {
            font-size: 1rem;
            margin-bottom: 32px;
        }

        .story-mission .testimonial-bar {
            grid-template-columns: 1fr;
            gap: 24px;
            padding: 28px 18px !important;
            border-radius: 24px;
        }
    }
</style>
@endpush

@section('content')
<header class="page-hero">
    <div class="container">
        <h1>The Heart of <span>Pure Water</span></h1>
        <p>Since 2026, Aqua Heart has been pioneering the science of healthy hydration. Discover why 5,000+ families trust us every single day.</p>
    </div>
</header>

<section class="story-section" id="process">
    <div class="container">
        <div class="story-grid">
            <div class="story-media reveal">
                <img src="{{ asset('aqua_heart_hero_premium.png') }}" alt="Our Lab" class="story-image">
            </div>
            <div class="story-content reveal">
                <h2>Precision in Every Drop</h2>
                <p>We believe that water is the foundation of health. That's why we invested in a world-class filtration system that goes far beyond standard tap water. Our molecular filtration system removes 99.9% of all contaminants, ensuring your family receives only the purest hydration.</p>
                
                <div class="process-grid">
                    <div class="process-card">
                        <h4>24-Stage Process</h4>
                        <p>Our elaborate purification process includes UV sterilization and extreme reverse osmosis.</p>
                    </div>
                    <div class="process-card">
                        <h4>Mineral Recharge</h4>
                        <p>We don't just take things out; we put the good stuff back in for a perfectly balanced pH.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="section story-mission" id="sustainability">
    <div class="container story-mission-inner">
        <h2>Sustainability & Mission</h2>
        <p>To provide every household in the region with accessible, life-improving hydration while maintaining the highest standards of safety and sustainability.</p>
        
        <div class="testimonial-bar" style="background: rgba(255,255,255,0.05); padding: 60px;">
            <div class="t-item">
                <i data-lucide="check-circle" size="48" style="color: var(--aqua-400); margin-bottom: 20px;"></i>
                <h4>Safety First</h4>
                <p>Daily testing of our chemical balance and bacterial levels.</p>
            </div>
            <div class="t-item">
                <i data-lucide="leaf" size="48" style="color: var(--aqua-400); margin-bottom: 20px;"></i>
                <h4>Eco-Friendly</h4>
                <p>Advanced recycling for our BPA-free industrial-grade bottles.</p>
            </div>
            <div class="t-item">
                <i data-lucide="shield-check" size="48" style="color: var(--aqua-400); margin-bottom: 20px;"></i>
                <h4>Certified Pure</h4>
                <p>Fully compliant with all national health and safety standards.</p>
            </div>
        </div>
    </div>
</section>
@endsection
