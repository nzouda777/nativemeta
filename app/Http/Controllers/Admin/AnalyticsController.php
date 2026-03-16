<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AnalyticsController extends Controller
{
    /**
     * Display analytics dashboard.
     */
    public function index(Request $request)
    {
        $startDate = $request->get('start_date') 
            ? Carbon::parse($request->get('start_date')) 
            : Carbon::now()->subDays(30);
            
        $endDate = $request->get('end_date') 
            ? Carbon::parse($request->get('end_date')) 
            : Carbon::now();

        // Statistiques de visites
        $visitStats = $this->getVisitStats($startDate, $endDate);
        
        // Statistiques de commandes
        $orderStats = $this->getOrderStats($startDate, $endDate);
        
        // Données pour les graphiques
        $chartData = $this->getChartData($startDate, $endDate);

        return inertia('Admin/Analytics', [
            'visitStats' => $visitStats,
            'orderStats' => $orderStats,
            'chartData' => $chartData,
            'filters' => [
                'start_date' => $startDate->format('Y-m-d'),
                'end_date' => $endDate->format('Y-m-d'),
            ],
        ]);
    }

    /**
     * Get visit statistics.
     */
    private function getVisitStats($startDate, $endDate)
    {
        // Si vous avez une table visits, utilisez-la
        // Sinon, simulez avec les logs ou analytics
        
        // Simulation de données de visites (à adapter selon votre tracking)
        $totalVisits = DB::table('activity_log')
            ->where('created_at', '>=', $startDate)
            ->where('created_at', '<=', $endDate)
            ->count();

        $uniqueVisitors = DB::table('activity_log')
            ->where('created_at', '>=', $startDate)
            ->where('created_at', '<=', $endDate)
            ->distinct('causer_id')
            ->count('causer_id');

        $avgDailyVisits = $totalVisits > 0 
            ? round($totalVisits / $startDate->diffInDays($endDate) + 1, 2)
            : 0;

        return [
            'total_visits' => $totalVisits,
            'unique_visitors' => $uniqueVisitors,
            'avg_daily_visits' => $avgDailyVisits,
            'bounce_rate' => $this->calculateBounceRate($startDate, $endDate),
            'avg_session_duration' => $this->calculateAvgSessionDuration($startDate, $endDate),
        ];
    }

    /**
     * Get order statistics.
     */
    private function getOrderStats($startDate, $endDate)
    {
        $orders = Order::where('created_at', '>=', $startDate)
            ->where('created_at', '<=', $endDate);

        $totalOrders = $orders->count();
        $totalRevenue = $orders->sum('amount');
        $avgOrderValue = $totalOrders > 0 ? round($totalRevenue / $totalOrders, 2) : 0;
        
        $completedOrders = $orders->where('status', 'completed')->count();
        $conversionRate = $totalOrders > 0 
            ? round(($completedOrders / $totalOrders) * 100, 2) 
            : 0;

        return [
            'total_orders' => $totalOrders,
            'completed_orders' => $completedOrders,
            'total_revenue' => $totalRevenue,
            'avg_order_value' => $avgOrderValue,
            'conversion_rate' => $conversionRate,
        ];
    }

    /**
     * Get chart data for visualization.
     */
    private function getChartData($startDate, $endDate)
    {
        $days = $startDate->diffInDays($endDate) + 1;
        $period = $days > 90 ? 'month' : ($days > 30 ? 'week' : 'day');

        switch ($period) {
            case 'day':
                return $this->getDailyData($startDate, $endDate);
            case 'week':
                return $this->getWeeklyData($startDate, $endDate);
            case 'month':
                return $this->getMonthlyData($startDate, $endDate);
            default:
                return $this->getDailyData($startDate, $endDate);
        }
    }

    /**
     * Get daily chart data.
     */
    private function getDailyData($startDate, $endDate)
    {
        $visits = DB::table('activity_log')
            ->selectRaw('DATE(created_at) as date, COUNT(*) as visits')
            ->where('created_at', '>=', $startDate)
            ->where('created_at', '<=', $endDate)
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        $orders = Order::selectRaw('DATE(created_at) as date, COUNT(*) as orders, SUM(amount) as revenue')
            ->where('created_at', '>=', $startDate)
            ->where('created_at', '<=', $endDate)
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        return [
            'labels' => $this->generateDateLabels($startDate, $endDate, 'day'),
            'visits' => $this->alignChartData($visits, $startDate, $endDate, 'day'),
            'orders' => $this->alignChartData($orders, $startDate, $endDate, 'day'),
            'revenue' => $this->alignChartData($orders, $startDate, $endDate, 'day', 'revenue'),
        ];
    }

    /**
     * Get weekly chart data.
     */
    private function getWeeklyData($startDate, $endDate)
    {
        $visits = DB::table('activity_log')
            ->selectRaw('YEARWEEK(created_at) as week, COUNT(*) as visits')
            ->where('created_at', '>=', $startDate)
            ->where('created_at', '<=', $endDate)
            ->groupBy('week')
            ->orderBy('week')
            ->get();

        $orders = Order::selectRaw('YEARWEEK(created_at) as week, COUNT(*) as orders, SUM(amount) as revenue')
            ->where('created_at', '>=', $startDate)
            ->where('created_at', '<=', $endDate)
            ->groupBy('week')
            ->orderBy('week')
            ->get();

        return [
            'labels' => $this->generateDateLabels($startDate, $endDate, 'week'),
            'visits' => $this->alignChartData($visits, $startDate, $endDate, 'week'),
            'orders' => $this->alignChartData($orders, $startDate, $endDate, 'week'),
            'revenue' => $this->alignChartData($orders, $startDate, $endDate, 'week', 'revenue'),
        ];
    }

    /**
     * Get monthly chart data.
     */
    private function getMonthlyData($startDate, $endDate)
    {
        $visits = DB::table('activity_log')
            ->selectRaw('YEAR(created_at) as year, MONTH(created_at) as month, COUNT(*) as visits')
            ->where('created_at', '>=', $startDate)
            ->where('created_at', '<=', $endDate)
            ->groupBy('year', 'month')
            ->orderBy('year')
            ->orderBy('month')
            ->get();

        $orders = Order::selectRaw('YEAR(created_at) as year, MONTH(created_at) as month, COUNT(*) as orders, SUM(amount) as revenue')
            ->where('created_at', '>=', $startDate)
            ->where('created_at', '<=', $endDate)
            ->groupBy('year', 'month')
            ->orderBy('year')
            ->orderBy('month')
            ->get();

        return [
            'labels' => $this->generateDateLabels($startDate, $endDate, 'month'),
            'visits' => $this->alignChartData($visits, $startDate, $endDate, 'month'),
            'orders' => $this->alignChartData($orders, $startDate, $endDate, 'month'),
            'revenue' => $this->alignChartData($orders, $startDate, $endDate, 'month', 'revenue'),
        ];
    }

    /**
     * Generate date labels for charts.
     */
    private function generateDateLabels($startDate, $endDate, $period)
    {
        $labels = [];
        $current = $startDate->copy();

        switch ($period) {
            case 'day':
                while ($current <= $endDate) {
                    $labels[] = $current->format('d/m');
                    $current->addDay();
                }
                break;
            case 'week':
                while ($current <= $endDate) {
                    $labels[] = 'S' . $current->weekOfYear;
                    $current->addWeek();
                }
                break;
            case 'month':
                while ($current <= $endDate) {
                    $labels[] = $current->format('M Y');
                    $current->addMonth();
                }
                break;
        }

        return $labels;
    }

    /**
     * Align chart data with labels.
     */
    private function alignChartData($data, $startDate, $endDate, $period, $field = null)
    {
        $aligned = [];
        $current = $startDate->copy();

        switch ($period) {
            case 'day':
                while ($current <= $endDate) {
                    $dateStr = $current->format('Y-m-d');
                    $item = $data->firstWhere('date', $dateStr);
                    $aligned[] = $item ? ($field ? $item->$field : $item->visits ?? $item->orders ?? 0) : 0;
                    $current->addDay();
                }
                break;
            case 'week':
                while ($current <= $endDate) {
                    $week = $current->weekOfYear;
                    $item = $data->firstWhere('week', $week);
                    $aligned[] = $item ? ($field ? $item->$field : $item->visits ?? $item->orders ?? 0) : 0;
                    $current->addWeek();
                }
                break;
            case 'month':
                while ($current <= $endDate) {
                    $year = $current->year;
                    $month = $current->month;
                    $item = $data->firstWhere('year', $year)->firstWhere('month', $month);
                    $aligned[] = $item ? ($field ? $item->$field : $item->visits ?? $item->orders ?? 0) : 0;
                    $current->addMonth();
                }
                break;
        }

        return $aligned;
    }

    /**
     * Calculate bounce rate.
     */
    private function calculateBounceRate($startDate, $endDate)
    {
        // Logique à adapter selon votre tracking
        return 35.5; // Exemple
    }

    /**
     * Calculate average session duration.
     */
    private function calculateAvgSessionDuration($startDate, $endDate)
    {
        // Logique à adapter selon votre tracking
        return '2m 45s'; // Exemple
    }

    /**
     * Export analytics data.
     */
    public function export(Request $request)
    {
        $startDate = $request->get('start_date') 
            ? Carbon::parse($request->get('start_date')) 
            : Carbon::now()->subDays(30);
            
        $endDate = $request->get('end_date') 
            ? Carbon::parse($request->get('end_date')) 
            : Carbon::now();

        $visitStats = $this->getVisitStats($startDate, $endDate);
        $orderStats = $this->getOrderStats($startDate, $endDate);
        $chartData = $this->getChartData($startDate, $endDate);

        $exportData = [
            'period' => [
                'start_date' => $startDate->format('Y-m-d'),
                'end_date' => $endDate->format('Y-m-d'),
            ],
            'visit_statistics' => $visitStats,
            'order_statistics' => $orderStats,
            'chart_data' => $chartData,
            'exported_at' => now()->format('Y-m-d H:i:s'),
        ];

        $filename = 'analytics_' . $startDate->format('Y-m-d') . '_to_' . $endDate->format('Y-m-d') . '.json';

        return response()->json($exportData, 200, [
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ]);
    }
}
