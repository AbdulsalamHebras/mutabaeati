<?php

namespace App\Filament\Widgets;

use Filament\Widgets\ChartWidget;
use App\Models\Specialization;

class StudentsBySpecializationChart extends ChartWidget
{
    protected static ?string $heading = 'الطلاب حسب التخصص';
    protected static ?int $sort = 2;

    protected function getData(): array
    {
        $specializations = Specialization::withCount('students')->having('students_count', '>', 0)->get();

        $labels = $specializations->pluck('name')->toArray();
        $data = $specializations->pluck('students_count')->toArray();

        // Generate distinct random colors for the pie chart slices
        $colors = [
            '#F87171', // Red
            '#60A5FA', // Blue
            '#34D399', // Emerald
            '#FBBF24', // Amber
            '#A78BFA', // Purple
            '#F472B6', // Pink
            '#38BDF8', // Light Blue
            '#FB923C', // Orange
        ];

        return [
            'datasets' => [
                [
                    'label' => 'عدد الطلاب',
                    'data' => $data,
                    'backgroundColor' => array_slice($colors, 0, count($data)),
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'pie';
    }
}
