<?php

namespace App\Http\Controllers;

use App\Models\Refill;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Show the dashboard
     */
    public function index()
    {
        $today = now()->startOfDay();
        
        // Sales metrics
        $todaySales = Refill::where('created_at', '>=', $today)
            ->get()
            ->sum(fn($r) => $r->quantity * $r->unit_price);
        
        $yesterdaySales = Refill::whereBetween('created_at', [
            now()->subDay()->startOfDay(),
            now()->subDay()->endOfDay()
        ])
            ->get()
            ->sum(fn($r) => $r->quantity * $r->unit_price);
        
        $salesTrendPercent = $yesterdaySales > 0 
            ? (($todaySales - $yesterdaySales) / $yesterdaySales) * 100 
            : 100;
        
        // Refills
        $todayRefills = Refill::where('created_at', '>=', $today)->count();
        $todayLiters = Refill::where('created_at', '>=', $today)->sum('quantity') * 20;
        
        // Active deliveries
        $activeDeliveries = Refill::whereHas('serviceType', fn($q) => $q->where('name', 'delivery'))
            ->whereDate('created_at', today())
            ->count();
        
        // Pending dispatch count
        $pendingDispatch = Refill::whereHas('serviceType', fn($q) => $q->where('name', 'delivery'))
            ->whereHas('paymentStatus', fn($q) => $q->where('name', 'paid'))
            ->whereDate('created_at', today())
            ->count();
        
        // Recent transactions
        $recentTransactions = Refill::with(['customer', 'product', 'user'])
            ->latest()
            ->take(3)
            ->get();

        // Chart Data (Last 7 Days)
        $dailySales = Refill::selectRaw('DATE(created_at) as date, SUM(quantity * unit_price) as total')
            ->where('created_at', '>=', now()->subDays(6)->startOfDay())
            ->groupBy('date')
            ->orderBy('date')
            ->pluck('total', 'date')
            ->toArray();
        
        $chartLabels = [];
        $chartValues = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i)->format('Y-m-d');
            $chartLabels[] = now()->subDays($i)->format('D');
            $chartValues[] = (float) ($dailySales[$date] ?? 0);
        }

        $chartColors = [];
        foreach ($chartValues as $index => $value) {
            $chartColors[] = $index % 2 === 0 ? 'rgba(2, 132, 199, 0.4)' : '#0284c7';
        }
        
        // System health/reservoir data
        $systemHealth = $this->getSystemHealth();
        
        return view('aquaheart.dashboard', compact(
            'todaySales',
            'yesterdaySales',
            'salesTrendPercent',
            'todayRefills',
            'todayLiters',
            'activeDeliveries',
            'pendingDispatch',
            'recentTransactions',
            'chartLabels',
            'chartValues',
            'chartColors',
            'systemHealth'
        ));
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
        
        // Calculate days remaining
        $avgDailyConsumption = $this->calculateAverageDailyConsumption();
        $daysRemaining = $avgDailyConsumption > 0 
            ? ceil($currentLevel / $avgDailyConsumption)
            : 0;
        
        // Determine pump status
        $pumpStatus = $percentFull > 20 ? 'STABLE' : 'WARNING';
        
        return [
            'percentage' => round($percentFull),
            'liters' => round($currentLevel),
            'days_remaining' => $daysRemaining,
            'pump_status' => $pumpStatus,
            'status_class' => $pumpStatus === 'STABLE' ? 'healthy' : 'warning'
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
    
    /**
     * Calculate average daily consumption
     */
    private function calculateAverageDailyConsumption()
    {
        $days = 7; // last 7 days
        $consumption = Refill::where('created_at', '>=', now()->subDays($days))
            ->sum('quantity') * 20; // 20L per unit
        
        return $consumption / $days;
    }
}
