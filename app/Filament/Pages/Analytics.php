<?php

namespace App\Filament\Pages;

use App\Models\Order;
use App\Models\User;
use Spatie\Activitylog\Models\Activity;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class Analytics extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-chart-bar';
    protected static ?string $navigationGroup = 'Analytique';
    protected static ?string $navigationLabel = 'Analytique';
    protected static ?string $title = 'Tableau de bord analytique';
    protected static string $view = 'filament.pages.analytics';

    public ?array $data = [];

    public function mount(): void
    {
        $this->form->fill([
            'start_date' => Carbon::now()->subDays(30)->format('Y-m-d'),
            'end_date' => Carbon::now()->format('Y-m-d'),
        ]);
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Filtres de date')
                    ->description('Sélectionnez une période pour analyser les données')
                    ->schema([
                        DatePicker::make('start_date')
                            ->label('Date de début')
                            ->required()
                            ->maxDate('now'),
                        DatePicker::make('end_date')
                            ->label('Date de fin')
                            ->required()
                            ->maxDate('now'),
                    ])
                    ->columns(2),
            ])
            ->statePath('data');
    }

    public function updateFilters(): void
    {
        $this->validate();
        $this->dispatch('filtersUpdated');
    }

    public function getStatistics(): array
    {
        $startDate = Carbon::parse($this->data['start_date'] ?? Carbon::now()->subDays(30));
        $endDate = Carbon::parse($this->data['end_date'] ?? Carbon::now());

        // Statistiques de visites (basées sur les logs d'activité)
        $totalVisits = Activity::where('created_at', '>=', $startDate)
            ->where('created_at', '<=', $endDate)
            ->count();

        $uniqueVisitors = Activity::where('created_at', '>=', $startDate)
            ->where('created_at', '<=', $endDate)
            ->whereNotNull('causer_id')
            ->distinct('causer_id')
            ->count('causer_id');

        // Statistiques de commandes
        $orders = Order::where('created_at', '>=', $startDate)
            ->where('created_at', '<=', $endDate);

        $totalOrders = $orders->count();
        $totalRevenue = $orders->sum('amount');
        $completedOrders = $orders->where('status', 'paid')->count();
        $avgOrderValue = $totalOrders > 0 ? round($totalRevenue / $totalOrders, 2) : 0;
        $conversionRate = $totalOrders > 0 ? round(($completedOrders / $totalOrders) * 100, 2) : 0;

        return [
            'visits' => [
                'total' => $totalVisits,
                'unique_visitors' => $uniqueVisitors,
                'avg_daily' => $totalVisits > 0 ? round($totalVisits / $startDate->diffInDays($endDate) + 1, 2) : 0,
            ],
            'orders' => [
                'total' => $totalOrders,
                'completed' => $completedOrders,
                'revenue' => $totalRevenue,
                'avg_value' => $avgOrderValue,
                'conversion_rate' => $conversionRate,
            ],
        ];
    }

    public function getChartData(): array
    {
        $startDate = Carbon::parse($this->data['start_date'] ?? Carbon::now()->subDays(30));
        $endDate = Carbon::parse($this->data['end_date'] ?? Carbon::now());

        // Données pour les graphiques
        $dailyData = Order::selectRaw('DATE(created_at) as date, COUNT(*) as orders, SUM(amount) as revenue')
            ->where('created_at', '>=', $startDate)
            ->where('created_at', '<=', $endDate)
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        $visitData = Activity::selectRaw('DATE(created_at) as date, COUNT(*) as visits')
            ->where('created_at', '>=', $startDate)
            ->where('created_at', '<=', $endDate)
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        // Aligner les données pour les graphiques
        $labels = [];
        $ordersData = [];
        $revenueData = [];
        $visitsData = [];

        $current = $startDate->copy();
        while ($current <= $endDate) {
            $dateStr = $current->format('Y-m-d');
            $labels[] = $current->format('d/m');
            
            $dayOrders = $dailyData->firstWhere('date', $dateStr);
            $dayVisits = $visitData->firstWhere('date', $dateStr);
            
            $ordersData[] = $dayOrders ? $dayOrders->orders : 0;
            $revenueData[] = $dayOrders ? $dayOrders->revenue : 0;
            $visitsData[] = $dayVisits ? $dayVisits->visits : 0;
            
            $current->addDay();
        }

        return [
            'labels' => $labels,
            'orders' => $ordersData,
            'revenue' => $revenueData,
            'visits' => $visitsData,
        ];
    }

    protected function getActions(): array
    {
        return [
            \Filament\Actions\Action::make('updateFilters')
                ->label('Appliquer les filtres')
                ->action('updateFilters')
                ->icon('heroicon-o-funnel'),
        ];
    }

    public function getTopCourses(): array
    {
        $startDate = Carbon::parse($this->data['start_date'] ?? Carbon::now()->subDays(30));
        $endDate = Carbon::parse($this->data['end_date'] ?? Carbon::now());

        return Order::join('order_items', 'orders.id', '=', 'order_items.order_id')
            ->join('courses', 'order_items.course_id', '=', 'courses.id')
            ->where('orders.created_at', '>=', $startDate)
            ->where('orders.created_at', '<=', $endDate)
            ->where('orders.status', 'paid')
            ->selectRaw('courses.title, COUNT(order_items.id) as sales, SUM(orders.amount) as revenue')
            ->groupBy('courses.id', 'courses.title')
            ->orderByDesc('sales')
            ->limit(10)
            ->get()
            ->toArray();
    }

    public function getRecentOrders(): array
    {
        return Order::with(['items.course'])
            ->orderByDesc('created_at')
            ->limit(10)
            ->get()
            ->map(function ($order) {
                return [
                    'id' => $order->id,
                    'email' => $order->email,
                    'amount' => '€' . number_format($order->amount, 2),
                    'status' => $order->status,
                    'created_at' => $order->created_at->format('d/m/Y H:i'),
                    'course' => $order->items->first()?->course?->title ?? 'N/A',
                ];
            })
            ->toArray();
    }
}
