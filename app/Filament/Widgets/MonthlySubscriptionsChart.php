<?php

namespace App\Filament\Widgets;

use Filament\Widgets\ChartWidget;
use App\Models\Subscription;
use App\Models\Month;
use Carbon\Carbon;

class MonthlySubscriptionsChart extends ChartWidget
{
    protected static ?string $heading = 'الاشتراكات الشهرية';
    protected static ?int $sort = 3;

    protected function getData(): array
    {
        $year = Carbon::now()->year;
        
        $englishMonths = [
            'January' => 1, 'February' => 2, 'March' => 3, 'April' => 4,
            'May' => 5, 'June' => 6, 'July' => 7, 'August' => 8,
            'September' => 9, 'October' => 10, 'November' => 11, 'December' => 12
        ];

        // Fetch all paid subscriptions for the current year based on their registered month
        $subscriptions = Subscription::where('is_paid', true)
            ->whereHas('month', function($q) use ($year) {
                $q->where('year', $year);
            })
            ->with('month')
            ->get()
            ->groupBy(function($val) use ($englishMonths) {
                return $englishMonths[$val->month->name] ?? 1;
            });

        $data = [];
        $labels = [];
        
        $arabicMonths = [
            1 => 'يناير', 2 => 'فبراير', 3 => 'مارس',
            4 => 'أبريل', 5 => 'مايو', 6 => 'يونيو',
            7 => 'يوليو', 8 => 'أغسطس', 9 => 'سبتمبر',
            10 => 'أكتوبر', 11 => 'نوفمبر', 12 => 'ديسمبر'
        ];

        for ($i = 1; $i <= 12; $i++) {
            $labels[] = $arabicMonths[$i];
            $data[] = isset($subscriptions[$i]) ? $subscriptions[$i]->sum('amount') : 0;
        }

        return [
            'datasets' => [
                [
                    'label' => 'إجمالي الاشتراكات (ريال)',
                    'data' => $data,
                    'borderColor' => '#3b82f6', // Filament primary blue
                    'backgroundColor' => 'rgba(59, 130, 246, 0.2)', // Semi-transparent blue
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}
