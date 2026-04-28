@extends('layouts.aquaheart')

@section('page_title', 'Help & Support')
@section('page_subtitle', 'Find answers, contact the team, or learn how to use the system.')

@push('styles')
<style>
    .support-grid {
        display: grid;
        grid-template-columns: 2fr 1fr;
        gap: 24px;
    }

    .support-card {
        background: white;
        border: 1px solid var(--border);
        border-radius: 20px;
        overflow: hidden;
        transition: var(--transition);
    }

    .support-card:hover {
        box-shadow: 0 12px 32px rgba(15, 23, 42, 0.08);
    }

    .support-card-header {
        padding: 24px;
        border-bottom: 1px solid var(--border);
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .support-card-header i {
        color: var(--accent);
        stroke-width: 2.5;
    }

    .support-card-header h2 {
        font-size: 1.1rem;
        font-weight: 800;
        color: var(--primary);
    }

    .support-content {
        padding: 24px;
    }

    .faq-item {
        margin-bottom: 24px;
    }

    .faq-item:last-child {
        margin-bottom: 0;
    }

    .faq-question {
        font-weight: 700;
        color: var(--primary);
        margin-bottom: 8px;
        display: flex;
        align-items: flex-start;
        gap: 10px;
        font-size: 0.95rem;
    }

    .faq-question i {
        color: var(--accent);
        margin-top: 2px;
    }

    .faq-answer {
        color: var(--text-muted);
        font-size: 0.9rem;
        line-height: 1.6;
        padding-left: 28px;
    }

    .contact-item {
        display: flex;
        align-items: center;
        gap: 16px;
        padding: 16px;
        background: var(--accent-soft);
        border-radius: 12px;
        margin-bottom: 16px;
        text-decoration: none;
        transition: var(--transition);
    }

    .contact-item:hover {
        transform: translateX(4px);
        background: #e0f2fe;
    }

    .contact-icon {
        width: 40px;
        height: 40px;
        background: white;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: var(--accent);
        box-shadow: 0 4px 12px rgba(59, 130, 246, 0.1);
    }

    .contact-info h3 {
        font-size: 0.85rem;
        font-weight: 800;
        color: var(--primary);
        margin-bottom: 2px;
    }

    .contact-info p {
        font-size: 0.8rem;
        color: var(--text-muted);
        font-weight: 600;
    }

    .quick-guide {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
        gap: 16px;
        margin-top: 12px;
    }

    .guide-box {
        padding: 16px;
        border: 1px dashed var(--border);
        border-radius: 12px;
        text-align: center;
        transition: var(--transition);
    }

    .guide-box:hover {
        border-color: var(--accent);
        background: var(--accent-soft);
    }

    .guide-box i {
        color: var(--accent);
        margin-bottom: 12px;
    }

    .guide-box h3 {
        font-size: 0.9rem;
        font-weight: 700;
        color: var(--primary);
        margin-bottom: 4px;
    }

    .guide-box p {
        font-size: 0.75rem;
        color: var(--text-muted);
    }

    @media (max-width: 1024px) {
        .support-grid {
            grid-template-columns: 1fr;
        }
    }
</style>
@endpush

@section('content')
<div class="support-grid">
    <div class="support-column">
        <div class="support-card" style="margin-bottom: 24px;">
            <div class="support-card-header">
                <i data-lucide="help-circle"></i>
                <h2>Frequently Asked Questions</h2>
            </div>
            <div class="support-content">
                <div class="faq-item">
                    <div class="faq-question">
                        <i data-lucide="chevron-right" size="16"></i>
                        How do I record a new transaction?
                    </div>
                    <div class="faq-answer">
                        Go to the "New Transaction" button in the sidebar or topbar. Select the customer, choose the products, and click "Complete Transaction".
                    </div>
                </div>

                <div class="faq-item">
                    <div class="faq-question">
                        <i data-lucide="chevron-right" size="16"></i>
                        How can I manage inventory?
                    </div>
                    <div class="faq-answer">
                        Navigate to the "Inventory" section. You can add new products, update existing ones, and monitor stock levels. Items with low stock will be highlighted.
                    </div>
                </div>

                <div class="faq-item">
                    <div class="faq-question">
                        <i data-lucide="chevron-right" size="16"></i>
                        Where can I see daily sales reports?
                    </div>
                    <div class="faq-answer">
                        Visit the "Sales Monitor" page. It provides a detailed breakdown of daily, weekly, and monthly sales performance with interactive charts.
                    </div>
                </div>

                <div class="faq-item">
                    <div class="faq-question">
                        <i data-lucide="chevron-right" size="16"></i>
                        What if a customer is not in the system?
                    </div>
                    <div class="faq-answer">
                        You can add a new customer directly from the "New Transaction" page or by going to the "Customers" management section and clicking "Add Customer".
                    </div>
                </div>
            </div>
        </div>

        <div class="support-card">
            <div class="support-card-header">
                <i data-lucide="book-open"></i>
                <h2>Quick Start Guide</h2>
            </div>
            <div class="support-content">
                <p style="font-size: 0.9rem; color: var(--text-muted); margin-bottom: 20px;">Get up to speed with the most common actions in the AquaHeart system.</p>
                <div class="quick-guide">
                    <div class="guide-box">
                        <i data-lucide="plus-circle" size="24"></i>
                        <h3>Transactions</h3>
                        <p>Process refills and sales quickly.</p>
                    </div>
                    <div class="guide-box">
                        <i data-lucide="users" size="24"></i>
                        <h3>Customers</h3>
                        <p>Track history and contact info.</p>
                    </div>
                    <div class="guide-box">
                        <i data-lucide="package" size="24"></i>
                        <h3>Inventory</h3>
                        <p>Manage products and stock.</p>
                    </div>
                    <div class="guide-box">
                        <i data-lucide="bar-chart" size="24"></i>
                        <h3>Reports</h3>
                        <p>Analyze business growth.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="support-column">
        <div class="support-card" style="margin-bottom: 24px;">
            <div class="support-card-header">
                <i data-lucide="message-square"></i>
                <h2>Contact Station</h2>
            </div>
            <div class="support-content">
                <p style="font-size: 0.85rem; color: var(--text-muted); margin-bottom: 20px;">Need immediate assistance from the station manager?</p>
                
                <a href="tel:09277377521" class="contact-item">
                    <div class="contact-icon">
                        <i data-lucide="phone" size="18"></i>
                    </div>
                    <div class="contact-info">
                        <h3>Globe / TM</h3>
                        <p>0927 737 7521</p>
                    </div>
                </a>

                <a href="tel:09309587024" class="contact-item">
                    <div class="contact-icon">
                        <i data-lucide="phone" size="18"></i>
                    </div>
                    <div class="contact-info">
                        <h3>Smart / TNT</h3>
                        <p>0930 958 7024</p>
                    </div>
                </a>

                <div class="contact-item" style="cursor: default; pointer-events: none;">
                    <div class="contact-icon">
                        <i data-lucide="map-pin" size="18"></i>
                    </div>
                    <div class="contact-info">
                        <h3>Location</h3>
                        <p>Basista, Pangasinan</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="support-card">
            <div class="support-card-header">
                <i data-lucide="terminal"></i>
                <h2>System Support</h2>
            </div>
            <div class="support-content">
                <p style="font-size: 0.85rem; color: var(--text-muted); margin-bottom: 16px;">Having technical issues with the software?</p>
                <div style="background: #f8fafc; border: 1px solid var(--border); padding: 16px; border-radius: 12px;">
                    <div style="display: flex; justify-content: space-between; margin-bottom: 8px;">
                        <span style="font-size: 0.75rem; font-weight: 700; color: var(--primary);">System Version</span>
                        <span style="font-size: 0.75rem; font-weight: 600; color: var(--text-muted);">v1.2.0-stable</span>
                    </div>
                    <div style="display: flex; justify-content: space-between;">
                        <span style="font-size: 0.75rem; font-weight: 700; color: var(--primary);">Last Updated</span>
                        <span style="font-size: 0.75rem; font-weight: 600; color: var(--text-muted);">April 28, 2026</span>
                    </div>
                </div>
                <button class="btn-primary" style="width: 100%; margin-top: 20px; justify-content: center;" onclick="alert('Technical support report feature coming soon!')">
                    <i data-lucide="bug" size="16"></i>
                    Report a Bug
                </button>
            </div>
        </div>
    </div>
</div>
@endsection
