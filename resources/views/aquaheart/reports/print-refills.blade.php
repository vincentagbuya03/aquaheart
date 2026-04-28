<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Aqua Heart - Refill Records Summary</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800&display=swap" rel="stylesheet">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Plus Jakarta Sans', sans-serif; padding: 50px; color: #1e293b; background: white; -webkit-print-color-adjust: exact; }
        .report-header { display: flex; justify-content: space-between; align-items: flex-start; padding-bottom: 40px; border-bottom: 2px solid #f1f5f9; margin-bottom: 40px; }
        .branding h1 { font-size: 24px; font-weight: 800; color: #0f172a; margin-bottom: 4px; }
        .branding p { font-size: 14px; color: #64748b; }
        .report-meta { text-align: right; }
        .report-meta h2 { font-size: 16px; font-weight: 700; text-transform: uppercase; letter-spacing: 1px; color: #3b82f6; margin-bottom: 8px; }
        .report-meta p { font-size: 13px; color: #64748b; line-height: 1.6; }
        .metrics-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 20px; margin-bottom: 40px; }
        .metric-item { background: #f8fafc; padding: 20px; border-radius: 12px; border: 1px solid #e2e8f0; }
        .metric-item span { display: block; font-size: 11px; font-weight: 800; color: #94a3b8; text-transform: uppercase; letter-spacing: 1px; margin-bottom: 6px; }
        .metric-item div { font-size: 18px; font-weight: 800; color: #0f172a; }
        .data-table { width: 100%; border-collapse: collapse; margin-bottom: 40px; }
        .data-table th { text-align: left; padding: 14px 18px; font-size: 11px; font-weight: 800; color: #64748b; text-transform: uppercase; background: #f8fafc; border-bottom: 1px solid #e2e8f0; }
        .data-table td { padding: 14px 18px; font-size: 13px; font-weight: 500; border-bottom: 1px solid #f1f5f9; color: #1e293b; }
        .data-table tr:last-child td { border-bottom: none; }
        .data-table .ref-cell { font-weight: 700; color: #0f172a; }
        .data-table .amt-cell { font-weight: 700; text-align: right; }
        .summary-total { margin-left: auto; width: 320px; padding: 24px; background: #0f172a; color: white; border-radius: 16px; display: flex; justify-content: space-between; align-items: center; }
        .summary-total span { font-size: 14px; opacity: 0.8; font-weight: 600; }
        .summary-total div { font-size: 22px; font-weight: 800; }
        .report-footer { margin-top: 60px; padding-top: 24px; border-top: 1px solid #f1f5f9; display: flex; justify-content: space-between; font-size: 11px; color: #94a3b8; }
        @media print {
            body { padding: 30px; }
            .metric-item { background: #f8fafc !important; }
            .summary-total { background: #0f172a !important; color: white !important; }
            @page { size: auto; margin: 0; }
        }
    </style>
</head>
<body>
    <header class="report-header">
        <div class="branding">
            <h1>Aqua Heart</h1>
            <p>Sales, Refill, and Customer Management System</p>
        </div>
        <div class="report-meta">
            <h2>Transaction Summary</h2>
            <p>REF-ID: {{ strtoupper(substr(md5(now()), 0, 8)) }}</p>
            <p>Generated: {{ date('M d, Y | h:i A') }}</p>
        </div>
    </header>

    <div class="metrics-grid">
        <div class="metric-item">
            <span>Total Records</span>
            <div>{{ $refills->count() }} Transactions</div>
        </div>
        <div class="metric-item">
            <span>Avg. Transaction</span>
            <div>₱ {{ number_format($refills->count() > 0 ? $refills->sum(fn($refill) => ($refill->quantity ?? 0) * ($refill->unit_price ?? 0)) / $refills->count() : 0, 2) }}</div>
        </div>
        <div class="metric-item">
            <span>Record Period</span>
            <div>{{ $refills->min('created_at') ? $refills->min('created_at')->format('M d') : 'N/A' }} - {{ $refills->max('created_at') ? $refills->max('created_at')->format('M d') : 'N/A' }}</div>
        </div>
    </div>

    <table class="data-table">
        <thead>
            <tr>
                <th>Receipt</th>
                <th>Processing Date</th>
                <th>Customer Name</th>
                <th>Product / Bottle</th>
                <th>Payment</th>
                <th style="text-align: right;">Amount</th>
            </tr>
        </thead>
        <tbody>
            @foreach($refills as $refill)
                <tr>
                    <td class="ref-cell">{{ $refill->receipt_number ?: 'Pending Number' }}</td>
                    <td>{{ optional($refill->created_at)->format('M d, Y') ?? 'N/A' }}</td>
                    <td>{{ $refill->customer->name ?? 'N/A' }}</td>
                    <td>{{ $refill->product->name ?? 'Standard' }}</td>
                    <td>{{ ucfirst(str_replace('_', ' ', $refill->computed_payment_status)) }}</td>
                    <td class="amt-cell">₱ {{ number_format(($refill->quantity ?? 0) * ($refill->unit_price ?? 0), 2) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="summary-total">
        <span>Accumulated Gross Total</span>
        <div>₱ {{ number_format($refills->sum(fn($refill) => ($refill->quantity ?? 0) * ($refill->unit_price ?? 0)), 2) }}</div>
    </div>

    <footer class="report-footer">
        <p>This document is an official administrative record of Aqua Heart station activity.</p>
        <p>Page 1 of 1</p>
    </footer>

    <script>
        window.onload = function() {
            window.print();
        }
    </script>
</body>
</html>
