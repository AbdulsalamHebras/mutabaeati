<?php

namespace App\Filament\Widgets;

use Filament\Widgets\ChartWidget;
use App\Models\Subscription;
use Carbon\Carbon;

class MonthlySubscriptionsChart extends ChartWidget
{
    protected static ?string $heading = 'الاشتراكات الشهرية';
    protected static ?int $sort = 3;

    protected function getData(): array
    {
        $year = Carbon::now()->year;
        
        // Fetch all paid subscriptions for the current year
        $subscriptions = Subscription::where('is_paid', true)
            ->whereYear('created_at', $year)
            ->get()
            ->groupBy(function($val) {
                return Carbon::parse($val->created_at)->format('n');
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
