<?php

namespace App\Http\Controllers;

use App\Models\Refill;
use Illuminate\Http\Request;
use Carbon\Carbon;

class CashierDashboardController extends Controller
{
    public function index()
    {
        // Today's sales
        $todayRevenue = (float) Refill::query()
            ->whereDate('created_at', Carbon::today())
            ->selectRaw('COALESCE(SUM(quantity * unit_price), 0) as total')
            ->value('total');
        $todayTransactions = Refill::whereDate('created_at', Carbon::today())->count();

        // Total sales (all time)
        $totalRevenue = (float) Refill::query()
            ->selectRaw('COALESCE(SUM(quantity * unit_price), 0) as total')
            ->value('total');
        $totalTransactions = Refill::count();

        // Monthly sales data for chart (last 12 months)
        $monthlySales = $this->getMonthlySalesData();

        // Daily sales data for last 30 days
        $dailySalesData = $this->getDailySalesData();

        return view('aquaheart.cashier.dashboard', [
            'todayRevenue' => $todayRevenue,
            'todayTransactions' => $todayTransactions,
            'totalRevenue' => $totalRevenue,
            'totalTransactions' => $totalTransactions,
            'monthlySalesData' => $monthlySales['data'],
            'monthlySalesLabels' => $monthlySales['labels'],
            'dailySalesData' => $dailySalesData['data'],
            'dailySalesLabels' => $dailySalesData['labels'],
        ]);
    }

    private function getMonthlySalesData()
    {
        $months = [];
        $data = [];
        
        for ($i = 11; $i >= 0; $i--) {
            $date = Carbon::now()->subMonths($i);
            $months[] = $date->format('M Y');
            
            $revenue = (float) Refill::query()
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

    private function getDailySalesData()
    {
        $days = [];
        $data = [];
        
        for ($i = 29; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i);
            $days[] = $date->format('M d');
            
            $revenue = (float) Refill::query()
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
