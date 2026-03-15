<?php

namespace App\Filament\Widgets;

use App\Models\Course;
use App\Models\Enrollment;
use App\Models\Order;
use App\Models\User;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends BaseWidget
{
    protected static ?int $sort = 1;

    protected function getStats(): array
    {
        $totalRevenue = Order::where('status', 'paid')->sum('amount');
        $monthRevenue = Order::where('status', 'paid')
            ->whereMonth('created_at', now()->month)
            ->sum('amount');

        $totalStudents = User::role('student')->count();
        $newStudentsThisMonth = User::role('student')
            ->whereMonth('created_at', now()->month)
            ->count();

        $publishedCourses = Course::where('status', 'published')->count();
        $draftCourses = Course::where('status', 'draft')->count();

        return [
            Stat::make('Revenus Totaux', number_format($totalRevenue, 2, ',', ' ') . ' €')
                ->description("+" . number_format($monthRevenue, 2, ',', ' ') . ' € ce mois')
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->color('success')
                ->chart([7, 3, 4, 5, 6, 3, 5, 8]),

            Stat::make('Clients Actifs', $totalStudents)
                ->description("+{$newStudentsThisMonth} ce mois")
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->color('info')
                ->chart([3, 5, 2, 7, 4, 6, 8, 5]),

            Stat::make('Formations', "{$publishedCourses} publiées")
                ->description("{$draftCourses} en brouillon")
                ->descriptionIcon('heroicon-m-academic-cap')
                ->color('warning'),

            Stat::make('Inscriptions', Enrollment::count())
                ->description(Enrollment::whereMonth('created_at', now()->month)->count() . ' ce mois')
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->color('success')
                ->chart([2, 4, 6, 5, 7, 8, 6, 9]),
        ];
    }
}
