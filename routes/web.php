<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\RefillController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\DashboardController;
use App\Models\Product;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\CashierDashboardController;
use App\Http\Controllers\CashierSettingsController;
use App\Http\Controllers\UserController;

// Public routes
Route::get('/', function () { return view('welcome'); })->name('home');
Route::get('/story', function () { return view('public.story'); })->name('story');
Route::get('/delivery', function () {
    $products = Product::query()
        ->where('is_active', true)
        ->orderBy('name')
        ->get(['id', 'name', 'price', 'description', 'stock_quantity', 'reorder_level']);

    $featuredProduct = $products->first();

    if (request()->filled('product')) {
        $featuredProduct = $products->firstWhere('id', request('product')) ?? $featuredProduct;
    }

    return view('public.delivery', compact('products', 'featuredProduct'));
})->name('delivery');
Route::get('/delivery/products/{product}', function (Product $product) {
    if (!$product->is_active) {
        abort(404);
    }

    $description = trim((string) ($product->description ?? ''));

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

    $stockQuantity = (int) ($product->stock_quantity ?? 0);
    $reorderLevel = max((int) ($product->reorder_level ?? 0), 1);
    $availabilityLabel = $stockQuantity <= 0
        ? 'Out of Stock'
        : ($stockQuantity <= $reorderLevel ? 'Limited Stock' : 'Available Today');

    return response()->json([
        'id' => $product->id,
        'name' => $product->name,
        'description' => $description !== ''
            ? $description
            : 'Freshly prepared drinking water gallons ready for refill and delivery.',
        'price' => (float) $product->price,
        'price_formatted' => number_format((float) $product->price, 2),
        'availability_label' => $availabilityLabel,
        'highlights' => $highlights->values()->all(),
        'strip_description' => \Illuminate\Support\Str::limit(
            $description !== ''
                ? $description
                : 'Clean gallon water prepared for regular delivery and refill needs.',
            100
        ),
    ]);
})->name('delivery.product-data');
Route::get('/contact', function () { return view('public.contact'); })->name('contact');
Route::get('/resources', function () { return view('public.resources'); })->name('resources');

// Authentication routes
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);

// Protected routes - require authentication
Route::middleware('auth')->group(function () {
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
    
    Route::prefix('aquaheart')->name('aquaheart.')->group(function () {
        Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
        Route::get('cashier', [CashierDashboardController::class, 'index'])->name('cashier.dashboard');
        
        // Cashier Settings
        Route::prefix('cashier/settings')->name('cashier.settings.')->group(function () {
            Route::get('/', [CashierSettingsController::class, 'index'])->name('index');
            Route::put('profile', [CashierSettingsController::class, 'updateProfile'])->name('updateProfile');
            Route::put('password', [CashierSettingsController::class, 'updatePassword'])->name('updatePassword');
        });
        
        Route::get('customers/search', [CustomerController::class, 'search'])->name('customers.search');

        Route::resource('users', UserController::class);
        Route::resource('customers', CustomerController::class);
        Route::resource('products', ProductController::class);
        Route::resource('refills', RefillController::class);
        Route::patch('refills/{refill}/payment-status', [RefillController::class, 'updatePaymentStatus'])->name('refills.payment-status.update');
        Route::post('refills/{refill}/payment-status', [RefillController::class, 'updatePaymentStatus'])->name('refills.payment-status.update.post');
        
        // Reports
        Route::prefix('reports')->name('reports.')->group(function () {
            Route::get('sales', [ReportController::class, 'sales'])->name('sales');
            Route::get('export-sales', [ReportController::class, 'exportSales'])->name('export-sales');
            Route::get('customers', [ReportController::class, 'customers'])->name('customers');
            Route::get('export-refills', [ReportController::class, 'exportRefills'])->name('export-refills');
            Route::get('print-refills', [ReportController::class, 'printRefills'])->name('print-refills');
        });
    });
});
