<?php

namespace App\Http\Controllers;

use App\Models\Refill;
use App\Models\Customer;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class ReportController extends Controller
{
    /**
     * Show sales/revenue report
     */
    public function sales()
    {
        if (!auth()->user()->is_admin) {
            abort(403, 'Unauthorized access to reports.');
        }

        $totalRevenue = (float) Refill::query()
            ->selectRaw('COALESCE(SUM(quantity * unit_price), 0) as total')
            ->value('total');
        $totalRefills = Refill::count();
        $paidTransactions = $this->countTransactionsByPaymentStatus('paid');
        $unpaidTransactions = $this->countTransactionsByPaymentStatus('unpaid');
        $partialTransactions = $this->countTransactionsByPaymentStatus('partial');
        $deliveryTransactions = $this->countTransactionsByServiceType('delivery');
        $lowStockProducts = \App\Models\Product::whereColumn('stock_quantity', '<=', 'reorder_level')
            ->orderBy('stock_quantity')
            ->get();
        
        // Daily revenue for last 30 days
        $dailyRevenue = Refill::selectRaw('DATE(created_at) as date, SUM(quantity * unit_price) as total, COUNT(*) as count')
            ->where('created_at', '>=', now()->subDays(30))
            ->groupBy('date')
            ->orderBy('date', 'desc')
            ->get();
        
        // Monthly revenue
        $monthlyRevenue = Refill::selectRaw('YEAR(created_at) as year, MONTH(created_at) as month, SUM(quantity * unit_price) as total, COUNT(*) as count')
            ->groupBy(DB::raw('YEAR(created_at), MONTH(created_at)'))
            ->orderBy('year', 'desc')
            ->orderBy('month', 'desc')
            ->limit(12)
            ->get();
        
        // Top customers by revenue
        $topCustomers = Customer::with('refills')
            ->selectRaw('customers.*, COALESCE(SUM(refills.quantity * refills.unit_price), 0) as total_spent')
            ->leftJoin('refills', 'customers.id', '=', 'refills.customer_id')
            ->groupBy('customers.id')
            ->orderBy('total_spent', 'desc')
            ->limit(10)
            ->get();
        
        // Today's revenue
        $todayRevenue = (float) Refill::query()
            ->whereDate('created_at', today())
            ->selectRaw('COALESCE(SUM(quantity * unit_price), 0) as total')
            ->value('total');
        $todayRefills = Refill::whereDate('created_at', today())->count();
        $todayVolume = (float) Refill::whereDate('created_at', today())->sum('quantity');

        // Recent Transactions
        $query = Refill::with(['customer', 'product']);
        
        if (request()->filled('type')) {
            $type = request('type');
            if ($type !== 'All') {
                $query->whereHas('product', function($q) use ($type) {
                    $q->where('name', 'like', "%{$type}%");
                });
            }
        }

        $recentTransactions = $query->latest()->paginate(10);
        
        // Get peak product and insights
        $peakProduct = $this->getPeakProduct();
        $lowStockAlert = $lowStockProducts->isNotEmpty() 
            ? $lowStockProducts->first()->name 
            : null;
        
        // System health metrics
        $systemHealth = $this->getSystemHealth();
            
        return view('aquaheart.reports.sales', compact(
            'totalRevenue',
            'totalRefills',
            'paidTransactions',
            'unpaidTransactions',
            'partialTransactions',
            'deliveryTransactions',
            'lowStockProducts',
            'dailyRevenue',
            'monthlyRevenue',
            'topCustomers',
            'todayRevenue',
            'todayRefills',
            'recentTransactions',
            'todayVolume',
            'peakProduct',
            'lowStockAlert',
            'systemHealth'
        ));
    }
    
    /**
     * Show customer statistics
     */
    public function customers()
    {
        if (!auth()->user()->is_admin) {
            abort(403, 'Unauthorized access to reports.');
        }

        $totalCustomers = Customer::count();
        
        $customerStats = Customer::selectRaw('
            customers.id,
            customers.name,
            COUNT(refills.id) as refill_count,
            COALESCE(SUM(refills.quantity * refills.unit_price), 0) as total_spent,
            COALESCE(AVG(refills.quantity * refills.unit_price), 0) as avg_spent,
            MAX(refills.created_at) as last_refill,
            MIN(refills.created_at) as first_refill
        ')
        ->leftJoin('refills', 'customers.id', '=', 'refills.customer_id')
        ->groupBy('customers.id', 'customers.name')
        ->orderBy('total_spent', 'desc')
        ->paginate(15);
        
        return view('aquaheart.reports.customers', compact('totalCustomers', 'customerStats'));
    }
    
    /**
     * Export refill records to CSV
     */
    public function exportRefills()
    {
        if (!auth()->user()->is_admin) {
            abort(403, 'Unauthorized access to reports.');
        }

        $refillsQuery = Refill::query()
            ->with(['customer', 'product'])
            ->select('refills.*')
            ->orderBy('refills.created_at', 'desc');

        if (Schema::hasColumn('refills', 'payment_status')) {
            $refillsQuery->addSelect('refills.payment_status as payment_status_label');
        } elseif (Schema::hasColumn('refills', 'payment_status_id')) {
            $refillsQuery->leftJoin('payment_statuses', 'payment_statuses.id', '=', 'refills.payment_status_id');

            if (Schema::hasColumn('payment_statuses', 'code')) {
                $refillsQuery->addSelect('payment_statuses.code as payment_status_label');
            } elseif (Schema::hasColumn('payment_statuses', 'name')) {
                $refillsQuery->addSelect(DB::raw("LOWER(REPLACE(payment_statuses.name, ' ', '_')) as payment_status_label"));
            }
        }

        if (Schema::hasColumn('refills', 'service_type')) {
            $refillsQuery->addSelect('refills.service_type as service_type_label');
        } elseif (Schema::hasColumn('refills', 'service_type_id')) {
            $refillsQuery->leftJoin('service_types', 'service_types.id', '=', 'refills.service_type_id');

            if (Schema::hasColumn('service_types', 'code')) {
                $refillsQuery->addSelect('service_types.code as service_type_label');
            } elseif (Schema::hasColumn('service_types', 'name')) {
                $refillsQuery->addSelect(DB::raw("LOWER(REPLACE(service_types.name, ' ', '_')) as service_type_label"));
            }
        }

        $refills = $refillsQuery->get();
        
        $filename = 'refill_records_' . date('Y-m-d_His') . '.csv';
        
        $headers = array(
            'Content-Type' => 'text/csv; charset=utf-8',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
        );
        
        $callback = function() use ($refills) {
            $file = fopen('php://output', 'w');
            
            // Write CSV header
            fputcsv($file, ['Receipt', 'Date', 'Customer', 'Product', 'Quantity', 'Service Type', 'Payment Status', 'Amount']);
            
            // Write data rows
            foreach ($refills as $refill) {
                $recordDate = optional($refill->created_at)->format('Y-m-d') ?? '';
                $excelSafeDate = '="' . $recordDate . '"';
                $lineAmount = ($refill->quantity ?? 0) * ($refill->unit_price ?? 0);

                fputcsv($file, [
                    $refill->receipt_number,
                    $excelSafeDate,
                    $refill->customer->name ?? 'N/A',
                    $refill->product->name ?? 'N/A',
                    $refill->quantity,
                    $refill->service_type_label ?? 'walk_in',
                    $refill->payment_status_label ?? 'paid',
                    'PHP ' . number_format($lineAmount, 2),
                ]);
            }
            
            fclose($file);
        };
        
        return response()->stream($callback, 200, $headers);
    }

            /**
             * Export sales summary to CSV
             */
            public function exportSales()
            {
                if (!auth()->user()->is_admin) {
                    abort(403, 'Unauthorized access to reports.');
                }

                $dailyRevenue = Refill::selectRaw('DATE(created_at) as date, SUM(quantity * unit_price) as total, COUNT(*) as count')
                    ->where('created_at', '>=', now()->subDays(30))
                    ->groupBy('date')
                    ->orderBy('date', 'desc')
                    ->get();

                $topCustomers = Customer::selectRaw('customers.name, COALESCE(SUM(refills.quantity * refills.unit_price), 0) as total_spent')
                    ->leftJoin('refills', 'customers.id', '=', 'refills.customer_id')
                    ->groupBy('customers.id', 'customers.name')
                    ->orderBy('total_spent', 'desc')
                    ->limit(10)
                    ->get();

                $lowStockProducts = Product::whereColumn('stock_quantity', '<=', 'reorder_level')
                    ->orderBy('stock_quantity')
                    ->get(['name', 'stock_quantity', 'reorder_level']);

                $filename = 'sales_summary_' . date('Y-m-d_His') . '.csv';

                $headers = [
                    'Content-Type' => 'text/csv; charset=utf-8',
                    'Content-Disposition' => "attachment; filename=\"$filename\"",
                ];

                $callback = function () use ($dailyRevenue, $topCustomers, $lowStockProducts) {
                    $file = fopen('php://output', 'w');

                    fputcsv($file, ['Sales Summary']);
                    fputcsv($file, ['Generated At', now()->format('Y-m-d H:i:s')]);
                    fputcsv($file, []);

                    fputcsv($file, ['Daily Revenue (Last 30 Days)']);
                    fputcsv($file, ['Date', 'Transactions', 'Revenue']);
                    foreach ($dailyRevenue as $day) {
                        fputcsv($file, [
                            \Carbon\Carbon::parse($day->date)->format('Y-m-d'),
                            $day->count,
                            number_format((float) $day->total, 2),
                        ]);
                    }

                    fputcsv($file, []);
                    fputcsv($file, ['Top Customers']);
                    fputcsv($file, ['Customer', 'Total Spent']);
                    foreach ($topCustomers as $customer) {
                        fputcsv($file, [
                            $customer->name,
                            number_format((float) $customer->total_spent, 2),
                        ]);
                    }

                    fputcsv($file, []);
                    fputcsv($file, ['Low Stock Products']);
                    fputcsv($file, ['Product', 'Stock', 'Reorder Level']);
                    foreach ($lowStockProducts as $product) {
                        fputcsv($file, [
                            $product->name,
                            $product->stock_quantity,
                            $product->reorder_level,
                        ]);
                    }

                    fclose($file);
                };

                return response()->stream($callback, 200, $headers);
            }
    
    /**
     * Print-friendly refill report
     */
    public function printRefills()
    {
        if (!auth()->user()->is_admin) {
            abort(403, 'Unauthorized access to reports.');
        }

        $refillsQuery = Refill::query()
            ->with(['customer', 'product'])
            ->select('refills.*')
            ->orderBy('refills.created_at', 'desc');

        if (Schema::hasColumn('refills', 'payment_status')) {
            $refillsQuery->addSelect('refills.payment_status as payment_status_label');
        } elseif (Schema::hasColumn('refills', 'payment_status_id')) {
            $refillsQuery->leftJoin('payment_statuses', 'payment_statuses.id', '=', 'refills.payment_status_id');

            if (Schema::hasColumn('payment_statuses', 'code')) {
                $refillsQuery->addSelect('payment_statuses.code as payment_status_label');
            } elseif (Schema::hasColumn('payment_statuses', 'name')) {
                $refillsQuery->addSelect(DB::raw("LOWER(REPLACE(payment_statuses.name, ' ', '_')) as payment_status_label"));
            }
        }

        $refills = $refillsQuery->get();
        
        return view('aquaheart.reports.print-refills', compact('refills'));
    }

    private function countTransactionsByPaymentStatus(string $status): int
    {
        if (Schema::hasColumn('refills', 'payment_status')) {
            return Refill::whereRaw('LOWER(payment_status) = ?', [strtolower($status)])->count();
        }

        if (!Schema::hasColumn('refills', 'payment_status_id')) {
            return 0;
        }

        $query = Refill::query()->leftJoin('payment_statuses', 'payment_statuses.id', '=', 'refills.payment_status_id');

        if (Schema::hasColumn('payment_statuses', 'code')) {
            return $query->whereRaw('LOWER(payment_statuses.code) = ?', [strtolower($status)])->count();
        }

        if (Schema::hasColumn('payment_statuses', 'name')) {
            return $query->whereRaw('LOWER(payment_statuses.name) = ?', [strtolower(str_replace('_', ' ', $status))])->count();
        }

        return 0;
    }

    private function countTransactionsByServiceType(string $serviceType): int
    {
        if (Schema::hasColumn('refills', 'service_type')) {
            return Refill::whereRaw('LOWER(service_type) = ?', [strtolower($serviceType)])->count();
        }

        if (!Schema::hasColumn('refills', 'service_type_id')) {
            return 0;
        }

        $query = Refill::query()->leftJoin('service_types', 'service_types.id', '=', 'refills.service_type_id');

        if (Schema::hasColumn('service_types', 'code')) {
            return $query->whereRaw('LOWER(service_types.code) = ?', [strtolower($serviceType)])->count();
        }

        if (Schema::hasColumn('service_types', 'name')) {
            return $query->whereRaw('LOWER(service_types.name) = ?', [strtolower(str_replace('_', ' ', $serviceType))])->count();
        }

        return 0;
    }
    
    /**
     * Get peak product for insights
     */
    private function getPeakProduct()
    {
        return Product::selectRaw('products.*, COUNT(refills.id) as refill_count, SUM(refills.quantity) as total_quantity')
            ->leftJoin('refills', 'products.id', '=', 'refills.product_id')
            ->whereDate('refills.created_at', today())
            ->groupBy('products.id')
            ->orderBy('refill_count', 'desc')
            ->first();
    }
    
    /**
     * Get system health metrics
     */
    private function getSystemHealth()
    {
        // Calculate system health based on actual data
        $totalCapacity = 1000; // gallons (configurable)
        $currentLevel = $this->calculateCurrentReservoirLevel();
        $percentFull = ($currentLevel / $totalCapacity) * 100;
        
        // Determine status
        $status = $percentFull > 30 ? 'Healthy Supply Level' : 'Low Supply Level';
        
        return [
            'percentage' => round($percentFull),
            'liters' => round($currentLevel),
            'status' => $status,
            'status_class' => $percentFull > 30 ? 'healthy' : 'warning'
        ];
    }
    
    /**
     * Calculate current reservoir level based on refills
     */
    private function calculateCurrentReservoirLevel()
    {
        // This is a simplified calculation - adjust based on your actual system
        $lastMonthConsumption = Refill::where('created_at', '>=', now()->subDays(30))
            ->sum('quantity') * 20; // 20L per unit
        
        $baseCapacity = 1000;
        $currentLevel = $baseCapacity - ($lastMonthConsumption % $baseCapacity);
        
        return max($currentLevel, 0);
    }
}
