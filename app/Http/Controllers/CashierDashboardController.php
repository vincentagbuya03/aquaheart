<?php

namespace App\Http\Controllers;

use App\Models\Refill;
use Illuminate\Http\Request;
use Carbon\Carbon;

class CashierDashboardController extends Controller
{
    public function index()
    {
        $userId = auth()->id();

        // Today's sales (Personal)
        $todayRevenue = (float) Refill::query()
            ->where('user_id', $userId)
            ->whereDate('created_at', Carbon::today())
            ->selectRaw('COALESCE(SUM(quantity * unit_price), 0) as total')
            ->value('total');
        $todayTransactionsCount = Refill::where('user_id', $userId)
            ->whereDate('created_at', Carbon::today())
            ->count();

        // Total sales (Personal - all time)
        $totalRevenue = (float) Refill::query()
            ->where('user_id', $userId)
            ->selectRaw('COALESCE(SUM(quantity * unit_price), 0) as total')
            ->value('total');
        $totalTransactionsCount = Refill::where('user_id', $userId)->count();

        // Recent Transactions (Personal)
        $recentTransactions = Refill::with(['customer', 'product'])
            ->where('user_id', $userId)
            ->latest()
            ->take(5)
            ->get();

        // Monthly sales data for chart (last 12 months - Personal)
        $monthlySales = $this->getMonthlySalesData($userId);

        // Daily sales data for last 30 days (Personal)
        $dailySalesData = $this->getDailySalesData($userId);

        return view('aquaheart.cashier.dashboard', [
            'todayRevenue' => $todayRevenue,
            'todayTransactions' => $todayTransactionsCount,
            'totalRevenue' => $totalRevenue,
            'totalTransactions' => $totalTransactionsCount,
            'recentTransactions' => $recentTransactions,
            'monthlySalesData' => $monthlySales['data'],
            'monthlySalesLabels' => $monthlySales['labels'],
            'dailySalesData' => $dailySalesData['data'],
            'dailySalesLabels' => $dailySalesData['labels'],
        ]);
    }

    private function getMonthlySalesData($userId)
    {
        $months = [];
        $data = [];
        
        for ($i = 11; $i >= 0; $i--) {
            $date = Carbon::now()->subMonths($i);
            $months[] = $date->format('M Y');
            
            $revenue = (float) Refill::query()
                ->where('user_id', $userId)
                ->whereYear('created_at', $date->year)
                ->whereMonth('created_at', $date->month)
                ->selectRaw('COALESCE(SUM(quantity * unit_price), 0) as total')
                ->value('total');
            
            $data[] = $revenue;
        }
        
        return [
            'labels' => $months,
            'data' => $data,
        ];
    }

    private function getDailySalesData($userId)
    {
        $days = [];
        $data = [];
        
        for ($i = 29; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i);
            $days[] = $date->format('M d');
            
            $revenue = (float) Refill::query()
                ->where('user_id', $userId)
                ->whereDate('created_at', $date)
                ->selectRaw('COALESCE(SUM(quantity * unit_price), 0) as total')
                ->value('total');
            $data[] = $revenue;
        }
        
        return [
            'labels' => $days,
            'data' => $data,
        ];
    }
}
