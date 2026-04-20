@extends('layouts.public')

@section('title', 'Resources')

@push('styles')
<style>
    .resources-page {
        padding: 72px 0 92px;
    }

    .resources-hero {
        text-align: center;
        max-width: 760px;
        margin: 0 auto 56px;
    }

    .resources-hero h1 {
        font-family: 'Manrope', sans-serif;
        font-size: clamp(2.6rem, 5vw, 4.2rem);
        letter-spacing: -0.05em;
        margin-bottom: 16px;
    }

    .resources-hero p {
        color: var(--slate-700);
        font-size: 1.05rem;
        line-height: 1.75;
    }

    .resource-grid {
        display: grid;
        grid-template-columns: repeat(3, minmax(0, 1fr));
        gap: 24px;
        margin-bottom: 44px;
    }

    .resource-card {
        background: white;
        border: 1px solid var(--aqua-100);
        border-radius: 28px;
        padding: 28px;
        box-shadow: 0 18px 40px rgba(15, 23, 42, 0.04);
    }

    .resource-card h2 {
        font-family: 'Manrope', sans-serif;
        font-size: 1.45rem;
        margin-bottom: 12px;
        letter-spacing: -0.03em;
    }

    .resource-card p {
        color: var(--slate-700);
        line-height: 1.7;
        margin-bottom: 16px;
    }

    .resource-card ul {
        list-style: none;
        display: grid;
        gap: 10px;
    }

    .resource-card li {
        color: var(--slate-700);
        font-size: 0.95rem;
        line-height: 1.6;
    }

    .resource-card li strong {
        color: var(--slate-900);
    }

    .resource-note {
        background: var(--aqua-50);
        border: 1px solid var(--aqua-100);
        border-radius: 28px;
        padding: 28px;
        color: var(--slate-700);
        line-height: 1.75;
    }

    @media (max-width: 1024px) {
        .resource-grid {
            grid-template-columns: 1fr;
        }
    }

    @media (max-width: 768px) {
        .resources-page {
            padding: 48px 0 72px;
        }

        .resource-card,
        .resource-note {
            border-radius: 22px;
            padding: 22px 18px;
        }

        .resources-hero h1 {
            font-size: 2.3rem;
        }

        .resources-hero p {
            font-size: 0.98rem;
        }
    }
</style>
@endpush

@section('content')
<section class="resources-page">
    <div class="container">
        <div class="resources-hero reveal">
            <h1>Resources for <span>Aqua Heart</span></h1>
            <p>Quick access to quality information, support details, and the terms that guide your use of Aqua Heart Purified Drinking Water.</p>
        </div>

        <div class="resource-grid">
            <article class="resource-card reveal" id="quality-report">
                <h2>Quality Report</h2>
                <p>Need reassurance about the water supply? Contact us and ask for the latest quality details for Aqua Heart service.</p>
                <ul>
                    <li><strong>What it covers:</strong> Service quality and station information</li>
                    <li><strong>How to request:</strong> Use the contact page or call the posted number</li>
                    <li><strong>Best for:</strong> Household and office buyers who want confirmation</li>
                </ul>
            </article>

            <article class="resource-card reveal" id="help-center">
                <h2>Help Center</h2>
                <p>Questions about delivery, refill scheduling, or ordering? Start with our contact page and reach the station directly.</p>
                <ul>
                    <li><strong>Phone:</strong> 09277377521</li>
                    <li><strong>Phone:</strong> 09309587024</li>
                    <li><strong>Location:</strong> Mapolopolo, Basista, Pangasinan</li>
                </ul>
            </article>

            <article class="resource-card reveal" id="terms-of-use">
                <h2>Terms of Use</h2>
                <p>Using the site means you agree to use the information responsibly and contact the station for order confirmation or service concerns.</p>
                <ul>
                    <li><strong>Orders:</strong> Confirm quantity and location before delivery</li>
                    <li><strong>Updates:</strong> Product and service details may change over time</li>
                    <li><strong>Support:</strong> Reach the team directly for clarifications</li>
                </ul>
            </article>
        </div>

        <div class="resource-note reveal">
            Aqua Heart Purified Drinking Water serves Mapolopolo, Basista, Pangasinan. For the quickest response, call 09277377521 or 09309587024.
        </div>
    </div>
</section>
@endsection
