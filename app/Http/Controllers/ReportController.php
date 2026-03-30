<?php

namespace App\Http\Controllers;

use App\Models\Refill;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    /**
     * Show sales/revenue report
     */
    public function sales()
    {
        $totalRevenue = Refill::sum('amount');
        $totalRefills = Refill::count();
        $paidTransactions = Refill::where('payment_status', 'paid')->count();
        $deliveryTransactions = Refill::where('service_type', 'delivery')->count();
        $lowStockProducts = \App\Models\Product::whereColumn('stock_quantity', '<=', 'reorder_level')
            ->orderBy('stock_quantity')
            ->get();
        
        // Daily revenue for last 30 days
        $dailyRevenue = Refill::selectRaw('DATE(refill_date) as date, SUM(amount) as total, COUNT(*) as count')
            ->where('refill_date', '>=', now()->subDays(30))
            ->groupBy('date')
            ->orderBy('date', 'desc')
            ->get();
        
        // Monthly revenue
        $monthlyRevenue = Refill::selectRaw('YEAR(refill_date) as year, MONTH(refill_date) as month, SUM(amount) as total, COUNT(*) as count')
            ->groupBy(DB::raw('YEAR(refill_date), MONTH(refill_date)'))
            ->orderBy('year', 'desc')
            ->orderBy('month', 'desc')
            ->limit(12)
            ->get();
        
        // Top customers by revenue
        $topCustomers = Customer::with('refills')
            ->selectRaw('customers.*, SUM(refills.amount) as total_spent')
            ->leftJoin('refills', 'customers.id', '=', 'refills.customer_id')
            ->groupBy('customers.id')
            ->orderBy('total_spent', 'desc')
            ->limit(10)
            ->get();
        
        // Today's revenue
        $todayRevenue = Refill::whereDate('refill_date', today())->sum('amount');
        $todayRefills = Refill::whereDate('refill_date', today())->count();
        
        return view('aquaheart.reports.sales', compact(
            'totalRevenue',
            'totalRefills',
            'paidTransactions',
            'deliveryTransactions',
            'lowStockProducts',
            'dailyRevenue',
            'monthlyRevenue',
            'topCustomers',
            'todayRevenue',
            'todayRefills'
        ));
    }
    
    /**
     * Show customer statistics
     */
    public function customers()
    {
        $totalCustomers = Customer::count();
        
        $customerStats = Customer::selectRaw('
            customers.id,
            customers.name,
            COUNT(refills.id) as refill_count,
            SUM(refills.amount) as total_spent,
            AVG(refills.amount) as avg_spent,
            MAX(refills.refill_date) as last_refill,
            MIN(refills.refill_date) as first_refill
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
        $refills = Refill::with(['customer', 'product'])
            ->orderBy('refill_date', 'desc')
            ->get();
        
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
                fputcsv($file, [
                    $refill->receipt_number,
                    $refill->refill_date->format('Y-m-d'),
                    $refill->customer->name,
                    $refill->product->name,
                    $refill->quantity,
                    $refill->service_type,
                    $refill->payment_status,
                    'PHP ' . number_format($refill->amount, 2),
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
        $refills = Refill::with(['customer', 'product'])
            ->orderBy('refill_date', 'desc')
            ->get();
        
        return view('aquaheart.reports.print-refills', compact('refills'));
    }
}
