@extends('layouts.public')

@section('title', 'Aqua Heart Purified Drinking Water | Contact')

@push('styles')
<style>
    .page-hero {
        padding: 108px 0 82px;
        background: linear-gradient(135deg, var(--aqua-50) 0%, #ffffff 100%);
        text-align: center;
    }

    .page-hero h1 {
        font-family: 'Manrope', sans-serif;
        font-size: clamp(2.5rem, 5vw, 4rem);
        letter-spacing: -0.05em;
        margin-bottom: 16px;
    }

    .page-hero p {
        max-width: 620px;
        margin: 0 auto;
        color: var(--slate-700);
        font-size: 1.02rem;
    }

    .contact-grid {
        display: grid;
        grid-template-columns: minmax(0, 1.05fr) minmax(320px, 0.95fr);
        gap: 36px;
        padding: 88px 0;
        align-items: start;
    }

    .contact-form-wrap {
        background: white;
        padding: 40px;
        border-radius: 32px;
        box-shadow: 0 30px 70px rgba(15, 23, 42, 0.05);
        border: 1px solid var(--aqua-100);
    }

    .contact-form-intro {
        margin-bottom: 26px;
    }

    .contact-form-intro h2 {
        font-family: 'Manrope', sans-serif;
        font-size: 2rem;
        letter-spacing: -0.04em;
        margin-bottom: 10px;
    }

    .contact-form-intro p {
        color: var(--slate-700);
    }

    .contact-form {
        display: grid;
        gap: 18px;
    }

    .contact-row {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 18px;
    }

    .field {
        display: grid;
        gap: 9px;
    }

    .field label {
        display: block;
        font-weight: 800;
        font-size: 0.82rem;
        text-transform: uppercase;
        color: var(--slate-900);
        letter-spacing: 0.06em;
    }

    .contact-form input,
    .contact-form textarea {
        width: 100%;
        padding: 16px 18px;
        border: 1px solid var(--aqua-100);
        border-radius: 16px;
        font: inherit;
        transition: var(--transition);
        background: #f8fbfe;
    }

    .contact-form input:focus,
    .contact-form textarea:focus {
        outline: none;
        border-color: var(--aqua-400);
        background: white;
        box-shadow: 0 0 0 4px rgba(56, 189, 248, 0.1);
    }

    .send-btn {
        width: 100%;
        justify-content: center;
        border: none;
        cursor: pointer;
        padding: 18px;
        font-size: 1rem;
    }

    .contact-info-col h3 {
        font-family: 'Manrope', sans-serif;
        font-size: 2rem;
        margin-bottom: 12px;
        letter-spacing: -0.04em;
    }

    .contact-info-col > p {
        color: var(--slate-700);
        margin-bottom: 24px;
    }

    .info-stack {
        display: grid;
        gap: 18px;
    }

    .info-card {
        background: var(--aqua-50);
        padding: 24px;
        border-radius: 24px;
        display: flex;
        gap: 18px;
        align-items: flex-start;
        border: 1px solid transparent;
        transition: var(--transition);
    }

    .info-card:hover {
        background: white;
        border-color: var(--aqua-100);
    }

    .info-card i {
        color: var(--aqua-600);
        flex-shrink: 0;
    }

    .info-card h4 {
        font-weight: 800;
        margin-bottom: 6px;
    }

    .info-card p {
        font-size: 0.94rem;
        color: var(--slate-700);
    }

    .info-note {
        margin-top: 22px;
        padding: 22px 24px;
        background: white;
        border-radius: 24px;
        border: 1px solid var(--aqua-100);
        color: var(--slate-700);
    }

    @media (max-width: 1100px) {
        .page-hero {
            padding: 92px 0 70px;
        }

        .contact-grid {
            grid-template-columns: 1fr;
            gap: 28px;
            padding: 64px 0;
        }
    }

    @media (max-width: 768px) {
        .page-hero {
            padding: 72px 0 52px;
        }

        .page-hero p {
            font-size: 0.96rem;
        }

        .contact-form-wrap {
            padding: 26px 20px;
            border-radius: 24px;
        }

        .contact-row {
            grid-template-columns: 1fr;
            gap: 18px;
        }

        .info-card {
            padding: 20px 18px;
            border-radius: 20px;
        }

        .contact-grid {
            padding: 48px 0;
        }

        .contact-info-col h3,
        .contact-form-intro h2 {
            font-size: 1.8rem;
        }

        .field label {
            font-size: 0.78rem;
        }
    }

    @media (max-width: 480px) {
        .page-hero {
            padding: 56px 0 42px;
        }

        .contact-grid {
            padding: 36px 0;
            gap: 20px;
        }

        .contact-form-wrap {
            padding: 22px 16px;
        }

        .info-card {
            gap: 14px;
        }

        .info-note {
            padding: 18px;
            border-radius: 20px;
        }
    }
</style>
@endpush

@section('content')
<header class="page-hero">
    <div class="container">
        <h1>Aqua Heart<br><span>Purified Drinking Water</span></h1>
        <p>"Guaranteed Safe and Clean from Pure Heart Within"</p>
    </div>
</header>

<div class="container">
    <div class="contact-grid">
        <section class="contact-form-wrap reveal">
            <div class="contact-form-intro">
                <h2>Send a Message</h2>
                <p>Reach out for delivery, refills, or general inquiries.</p>
            </div>

            <form class="contact-form">
                <div class="contact-row">
                    <div class="field">
                        <label for="full_name">Full Name</label>
                        <input id="full_name" type="text" placeholder="John Doe">
                    </div>

                    <div class="field">
                        <label for="email">Email Address</label>
                        <input id="email" type="email" placeholder="john@example.com">
                    </div>
                </div>

                <div class="field">
                    <label for="inquiry">Inquiry Type</label>
                    <input id="inquiry" type="text" placeholder="Home delivery, refill inquiry, or branch question">
                </div>

                <div class="field">
                    <label for="message">Message</label>
                    <textarea id="message" rows="5" placeholder="How can we help you today?"></textarea>
                </div>

                <button type="submit" class="nav-cta send-btn">
                    Send Message
                    <i data-lucide="send" size="18"></i>
                </button>
            </form>
        </section>

        <aside class="contact-info-col reveal">
            <h3>Reach Us</h3>
            <p>Contact Aqua Heart directly using the details below.</p>

            <div class="info-stack">
                <article class="info-card">
                    <i data-lucide="phone-call" size="28"></i>
                    <div>
                        <h4>Contact Numbers</h4>
                        <p>09277377521</p>
                        <p>09309587024</p>
                    </div>
                </article>

                <article class="info-card">
                    <i data-lucide="map-pin" size="28"></i>
                    <div>
                        <h4>Location</h4>
                        <p>Mapolopolo, Basista, Pangasinan</p>
                    </div>
                </article>
            </div>

            <div class="info-note">
                Aqua Heart Purified Drinking Water is available for delivery and refill inquiries in Mapolopolo, Basista, Pangasinan.
            </div>
        </aside>
    </div>
</div>
@endsection
