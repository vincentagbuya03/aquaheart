@extends('layouts.public')

@section('title', 'Gallons Delivery')

@push('styles')
<style>
    .delivery-page {
        padding: 72px 0 90px;
    }

    .delivery-hero {
        display: grid;
        grid-template-columns: 1.02fr 0.98fr;
        gap: 42px;
        align-items: center;
        margin-bottom: 72px;
    }

    .delivery-copy {
        max-width: 620px;
    }

    .delivery-kicker {
        display: inline-flex;
        align-items: center;
        gap: 10px;
        padding: 10px 16px;
        border-radius: 999px;
        background: var(--aqua-50);
        border: 1px solid var(--aqua-100);
        color: var(--aqua-600);
        font-size: 0.76rem;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: 0.08em;
        margin-bottom: 24px;
    }

    .delivery-copy h1 {
        font-family: 'Manrope', sans-serif;
        font-size: clamp(2.8rem, 6vw, 5rem);
        line-height: 0.95;
        letter-spacing: -0.06em;
        margin-bottom: 22px;
        color: var(--slate-900);
    }

    .delivery-copy h1 span {
        color: var(--aqua-600);
    }

    .delivery-copy p {
        font-size: 1.08rem;
        line-height: 1.75;
        color: var(--slate-700);
        margin-bottom: 30px;
        max-width: 34ch;
    }

    .delivery-actions {
        display: flex;
        gap: 16px;
        flex-wrap: wrap;
    }

    .primary-btn,
    .secondary-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 10px;
        padding: 16px 28px;
        border-radius: 18px;
        text-decoration: none;
        font-weight: 800;
        transition: var(--transition);
    }

    .primary-btn {
        background: var(--slate-900);
        color: white;
        box-shadow: 0 18px 34px rgba(15, 23, 42, 0.14);
    }

    .primary-btn:hover {
        transform: translateY(-2px);
        background: var(--aqua-600);
    }

    .secondary-btn {
        background: white;
        color: var(--slate-900);
        border: 1px solid var(--aqua-100);
    }

    .secondary-btn:hover {
        transform: translateY(-2px);
        border-color: var(--aqua-400);
    }

    .product-card {
        position: relative;
        background: linear-gradient(160deg, #0f172a 0%, #123250 58%, #0f766e 100%);
        border-radius: 40px;
        padding: 34px;
        color: white;
        box-shadow: 0 36px 80px rgba(15, 23, 42, 0.14);
        overflow: hidden;
    }

    .product-card::before {
        content: '';
        position: absolute;
        width: 280px;
        height: 280px;
        top: -80px;
        right: -60px;
        border-radius: 50%;
        background: rgba(125, 211, 252, 0.16);
        filter: blur(10px);
    }

    .product-card > * {
        position: relative;
        z-index: 1;
    }

    .product-card.is-loading {
        opacity: 0.75;
        transition: opacity 0.2s ease;
    }

    .product-pill {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 9px 14px;
        border-radius: 999px;
        background: rgba(255, 255, 255, 0.1);
        border: 1px solid rgba(255, 255, 255, 0.14);
        font-size: 0.76rem;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: 0.08em;
        margin-bottom: 22px;
    }

    .product-card h2 {
        font-family: 'Manrope', sans-serif;
        font-size: 2.3rem;
        letter-spacing: -0.05em;
        margin-bottom: 12px;
    }

    .product-card p {
        color: rgba(255, 255, 255, 0.78);
        font-size: 1rem;
        line-height: 1.7;
        margin-bottom: 28px;
        max-width: 30ch;
    }

    .price-tag {
        display: inline-flex;
        align-items: baseline;
        gap: 8px;
        margin-bottom: 26px;
    }

    .price-tag strong {
        font-size: 3rem;
        line-height: 1;
        font-weight: 800;
        color: #38bdf8;
    }

    .price-tag span {
        font-size: 1rem;
        font-weight: 700;
        color: rgba(255, 255, 255, 0.75);
    }

    .product-variants {
        display: flex;
        flex-wrap: wrap;
        gap: 8px;
        margin: 0 0 24px;
    }

    .product-variant {
        display: inline-flex;
        align-items: center;
        padding: 7px 12px;
        border-radius: 999px;
        text-decoration: none;
        font-size: 0.76rem;
        font-weight: 700;
        color: rgba(255, 255, 255, 0.82);
        border: 1px solid rgba(255, 255, 255, 0.22);
        background: rgba(255, 255, 255, 0.08);
        transition: var(--transition);
    }

    .product-variant:hover,
    .product-variant.active {
        color: #0f172a;
        border-color: rgba(103, 232, 249, 0.6);
        background: #67e8f9;
    }

    .product-list {
        list-style: none;
        display: grid;
        gap: 14px;
    }

    .product-list li {
        display: flex;
        align-items: center;
        gap: 12px;
        font-size: 0.96rem;
        font-weight: 600;
        color: rgba(255, 255, 255, 0.92);
    }

    .product-list li i {
        color: #67e8f9;
        flex-shrink: 0;
    }

    .delivery-strip {
        display: grid;
        grid-template-columns: repeat(3, minmax(0, 1fr));
        gap: 22px;
        margin-bottom: 72px;
    }

    .delivery-stat {
        background: white;
        border: 1px solid var(--aqua-100);
        border-radius: 28px;
        padding: 28px;
        box-shadow: 0 18px 40px rgba(15, 23, 42, 0.04);
    }

    .delivery-stat span {
        display: block;
        font-size: 0.78rem;
        text-transform: uppercase;
        letter-spacing: 0.08em;
        font-weight: 800;
        color: var(--aqua-600);
        margin-bottom: 10px;
    }

    .delivery-stat strong {
        display: block;
        font-size: 1.35rem;
        line-height: 1.3;
        margin-bottom: 8px;
        color: var(--slate-900);
    }

    .delivery-stat p {
        color: var(--slate-700);
        font-size: 0.95rem;
    }

    .delivery-info {
        background: var(--aqua-50);
        border-radius: 44px;
        padding: 72px 0;
    }

    .delivery-info-inner {
        display: grid;
        grid-template-columns: 0.95fr 1.05fr;
        gap: 36px;
        align-items: start;
    }

    .delivery-info-copy h2 {
        font-family: 'Manrope', sans-serif;
        font-size: clamp(2rem, 4vw, 3rem);
        letter-spacing: -0.04em;
        margin-bottom: 16px;
    }

    .delivery-info-copy p {
        max-width: 34ch;
        color: var(--slate-700);
        line-height: 1.75;
    }

    .steps-card {
        background: white;
        border: 1px solid var(--aqua-100);
        border-radius: 32px;
        padding: 28px;
        display: grid;
        gap: 18px;
    }

    .step {
        display: grid;
        grid-template-columns: 48px 1fr;
        gap: 16px;
        align-items: start;
    }

    .step-number {
        width: 48px;
        height: 48px;
        border-radius: 16px;
        background: var(--aqua-100);
        color: var(--aqua-600);
        font-weight: 800;
        display: grid;
        place-items: center;
    }

    .step h4 {
        font-size: 1.02rem;
        font-weight: 800;
        margin-bottom: 6px;
    }

    .step p {
        color: var(--slate-700);
        font-size: 0.95rem;
        line-height: 1.6;
    }

    @media (max-width: 1100px) {
        .delivery-page {
            padding: 56px 0 72px;
        }

        .delivery-hero,
        .delivery-info-inner,
        .delivery-strip {
            grid-template-columns: 1fr;
        }

        .delivery-copy,
        .delivery-copy p,
        .delivery-info-copy p {
            max-width: 100%;
        }

        .product-card {
            max-width: 760px;
        }
    }

    @media (max-width: 768px) {
        .delivery-page {
            padding: 40px 0 56px;
        }

        .delivery-hero {
            gap: 24px;
            margin-bottom: 44px;
        }

        .delivery-copy h1 {
            font-size: 2.6rem;
        }

        .delivery-copy p {
            font-size: 0.98rem;
        }

        .delivery-kicker {
            font-size: 0.7rem;
            padding: 8px 12px;
        }

        .delivery-actions {
            flex-direction: column;
        }

        .primary-btn,
        .secondary-btn {
            width: 100%;
        }

        .product-card,
        .delivery-stat,
        .steps-card {
            border-radius: 24px;
        }

        .product-card {
            padding: 26px 22px;
        }

        .product-card h2 {
            font-size: 1.9rem;
        }

        .product-list li,
        .delivery-stat p,
        .step p {
            font-size: 0.92rem;
        }

        .price-tag strong {
            font-size: 2.4rem;
        }

        .delivery-strip {
            margin-bottom: 44px;
            gap: 16px;
        }

        .delivery-info {
            border-radius: 28px;
            padding: 46px 0;
        }

        .steps-card {
            padding: 22px 18px;
        }

        .step {
            grid-template-columns: 40px 1fr;
            gap: 12px;
        }

        .step-number {
            width: 40px;
            height: 40px;
            border-radius: 12px;
        }
    }

    @media (max-width: 480px) {
        .delivery-copy h1 {
            font-size: 2.2rem;
        }

        .delivery-page {
            padding: 32px 0 48px;
        }

        .delivery-info {
            margin: 0 -8px;
            border-radius: 24px;
        }
    }
</style>
@endpush

@section('content')
@php
    $description = trim((string) ($featuredProduct?->description ?? ''));

    $highlights = collect(preg_split('/[\r\n]+/', $description ?: ''))
        ->map(fn ($line) => trim($line, " \t\n\r\0\x0B-•"))
        ->filter()
        ->take(4)
        ->values();

    if ($highlights->isEmpty() && $description !== '') {
        $highlights = collect(preg_split('/(?<=[.!?])\s+/', $description))
            ->map(fn ($line) => trim($line))
            ->filter(fn ($line) => strlen($line) > 8)
            ->take(4)
            ->values();
    }

    if ($highlights->isEmpty()) {
        $highlights = collect([
            'Clean and ready for daily use',
            'Good for regular household refill orders',
            'Local Aqua Heart delivery service',
            'Simple ordering with clear pricing',
        ]);
    }

    $stockQuantity = (int) ($featuredProduct?->stock_quantity ?? 0);
    $reorderLevel = max((int) ($featuredProduct?->reorder_level ?? 0), 1);
    $availabilityLabel = !$featuredProduct
        ? 'Inquire Today'
        : ($stockQuantity <= 0
            ? 'Out of Stock'
            : ($stockQuantity <= $reorderLevel ? 'Limited Stock' : 'Available Today'));
@endphp

<section class="delivery-page" id="home-refills">
    <div class="container">
        <div class="delivery-hero">
            <div class="delivery-copy">
                <div class="delivery-kicker">
                    <i data-lucide="droplet" size="16"></i>
                    Gallons Delivery
                </div>

                <h1>Simple delivery for your <span>water gallons.</span></h1>
                <p>
                    Aqua Heart delivers clean water gallons for home refills, office delivery, and bulk orders with a simple ordering process.
                </p>

                <div class="delivery-actions">
                    <a href="{{ route('contact') }}" class="primary-btn">
                        Order Now
                        <i data-lucide="arrow-right" size="18"></i>
                    </a>
                    <a href="tel:+123456789" class="secondary-btn">
                        <i data-lucide="phone" size="18"></i>
                        Call to Order
                    </a>
                </div>
            </div>

            <div class="product-card">
                <div class="product-pill">
                    <i data-lucide="badge-check" size="16"></i>
                    <span id="delivery-availability-label">{{ $availabilityLabel }}</span>
                </div>

                <h2 id="delivery-product-name">{{ $featuredProduct?->name ?? 'Water Gallons' }}</h2>
                <p id="delivery-product-description">
                    {{ $description ?: 'Freshly prepared drinking water gallons ready for refill and delivery.' }}
                </p>

                <div class="price-tag">
                    <strong id="delivery-product-price">PHP {{ number_format((float) ($featuredProduct?->price ?? 0), 2) }}</strong>
                    <span>per item</span>
                </div>

                @if (($products ?? collect())->count() > 1)
                    <div class="product-variants">
                        @foreach ($products as $product)
                            <a
                                href="{{ route('delivery', ['product' => $product->id]) }}"
                                class="product-variant {{ $featuredProduct?->id === $product->id ? 'active' : '' }}"
                                data-product-id="{{ $product->id }}"
                            >
                                {{ $product->name }}
                            </a>
                        @endforeach
                    </div>
                @endif

                <ul class="product-list" id="delivery-product-highlights">
                    @foreach ($highlights as $highlight)
                        <li><i data-lucide="check" size="18"></i> {{ $highlight }}</li>
                    @endforeach
                </ul>
            </div>
        </div>

        <div class="delivery-strip" id="office-delivery">
            <article class="delivery-stat reveal">
                <span>Product</span>
                <strong id="delivery-strip-product-name">{{ $featuredProduct?->name ?? 'Water Gallons' }}</strong>
                <p id="delivery-strip-product-description">{{ \Illuminate\Support\Str::limit($description ?: 'Clean gallon water prepared for regular delivery and refill needs.', 100) }}</p>
            </article>

            <article class="delivery-stat reveal">
                <span>Ordering</span>
                <strong>Fast and direct</strong>
                <p>Contact Aqua Heart and place your order without choosing from multiple plans.</p>
            </article>

            <article class="delivery-stat reveal">
                <span>Delivery</span>
                <strong>Home-ready service</strong>
                <p>Ideal for regular household water needs and quick reorder requests.</p>
            </article>
        </div>

        <section class="delivery-info" id="bulk-orders">
            <div class="container delivery-info-inner reveal">
                <div class="delivery-info-copy">
                    <h2>How to order</h2>
                    <p>
                        Ordering should feel easy. Just message or call Aqua Heart, confirm your quantity, and wait for your gallon water delivery. We also handle office delivery and bulk refill requests.
                    </p>
                </div>

                <div class="steps-card">
                    <article class="step">
                        <div class="step-number">1</div>
                        <div>
                            <h4>Send your inquiry</h4>
                            <p>Use the contact page or call the posted number to place your order.</p>
                        </div>
                    </article>

                    <article class="step">
                        <div class="step-number">2</div>
                        <div>
                            <h4>Confirm your quantity</h4>
                            <p>Tell us how many water gallons you need for delivery.</p>
                        </div>
                    </article>

                    <article class="step">
                        <div class="step-number">3</div>
                        <div>
                            <h4>Receive your order</h4>
                            <p>We prepare your water and deliver it to your location.</p>
                        </div>
                    </article>
                </div>
            </div>
        </section>
    </div>
</section>
@endsection

@push('scripts')
<script>
    (function () {
        var card = document.querySelector('.product-card');
        var variants = Array.prototype.slice.call(document.querySelectorAll('.product-variant[data-product-id]'));

        if (!card || variants.length === 0) {
            return;
        }

        var availabilityEl = document.getElementById('delivery-availability-label');
        var nameEl = document.getElementById('delivery-product-name');
        var descriptionEl = document.getElementById('delivery-product-description');
        var priceEl = document.getElementById('delivery-product-price');
        var highlightsEl = document.getElementById('delivery-product-highlights');
        var stripNameEl = document.getElementById('delivery-strip-product-name');
        var stripDescriptionEl = document.getElementById('delivery-strip-product-description');
        var activeRequest = null;

        function setActiveVariant(productId) {
            variants.forEach(function (link) {
                if (link.getAttribute('data-product-id') === productId) {
                    link.classList.add('active');
                } else {
                    link.classList.remove('active');
                }
            });
        }

        function renderHighlights(highlights) {
            var items = (highlights || []).map(function (text) {
                return '<li><i data-lucide="check" size="18"></i> ' + text + '</li>';
            }).join('');

            highlightsEl.innerHTML = items;
        }

        function updateHistoryUrl(productId) {
            var url = new URL(window.location.href);
            url.searchParams.set('product', productId);
            window.history.replaceState({}, '', url);
        }

        function fetchProduct(productId, fallbackHref) {
            if (activeRequest && typeof activeRequest.abort === 'function') {
                activeRequest.abort();
            }

            var controller = new AbortController();
            activeRequest = controller;

            card.classList.add('is-loading');

            fetch('{{ route('delivery.product-data', ['product' => '__PRODUCT__']) }}'.replace('__PRODUCT__', productId), {
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                signal: controller.signal
            })
                .then(function (response) {
                    if (!response.ok) {
                        throw new Error('Request failed');
                    }

                    return response.json();
                })
                .then(function (data) {
                    availabilityEl.textContent = data.availability_label;
                    nameEl.textContent = data.name;
                    descriptionEl.textContent = data.description;
                    priceEl.textContent = 'PHP ' + data.price_formatted;
                    stripNameEl.textContent = data.name;
                    stripDescriptionEl.textContent = data.strip_description;

                    renderHighlights(data.highlights);
                    setActiveVariant(data.id);
                    updateHistoryUrl(data.id);

                    if (window.lucide && typeof window.lucide.createIcons === 'function') {
                        window.lucide.createIcons();
                    }
                })
                .catch(function (error) {
                    if (error && error.name === 'AbortError') {
                        return;
                    }

                    window.location.href = fallbackHref;
                })
                .finally(function () {
                    if (activeRequest === controller) {
                        activeRequest = null;
                    }

                    card.classList.remove('is-loading');
                });
        }

        variants.forEach(function (link) {
            link.addEventListener('click', function (event) {
                event.preventDefault();

                var productId = link.getAttribute('data-product-id');
                if (!productId || link.classList.contains('active')) {
                    return;
                }

                fetchProduct(productId, link.href);
            });
        });
    })();
</script>
@endpush
