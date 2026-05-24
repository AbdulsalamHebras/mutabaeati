<?php

namespace App\Filament\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use App\Models\Student;
use App\Models\User;
use App\Models\University;
use App\Models\Subscription;
use App\Models\Month;
use Carbon\Carbon;

class StatsOverview extends BaseWidget
{
    protected function getStats(): array
    {
        $arabicMonths = [
            1 => 'يناير', 2 => 'فبراير', 3 => 'مارس',
            4 => 'أبريل', 5 => 'مايو', 6 => 'يونيو',
            7 => 'يوليو', 8 => 'أغسطس', 9 => 'سبتمبر',
            10 => 'أكتوبر', 11 => 'نوفمبر', 12 => 'ديسمبر'
        ];

        $currentCarbon = Carbon::now();
        $currentArabicName = $arabicMonths[$currentCarbon->month];
        $currentMonthId = Month::where('name', $currentCarbon->format('F'))
            ->where('year', $currentCarbon->year)
            ->value('id');

        $prevCarbon = Carbon::now()->subMonth();
        $prevArabicName = $arabicMonths[$prevCarbon->month];
        $prevMonthId = Month::where('name', $prevCarbon->format('F'))
            ->where('year', $prevCarbon->year)
            ->value('id');

        $currentMonthAmount = Subscription::where('is_paid', true)
            ->where('month_id', $currentMonthId)
            ->sum('amount');

        $prevMonthAmount = Subscription::where('is_paid', true)
            ->where('month_id', $prevMonthId)
            ->sum('amount');

        return [
            Stat::make('عدد الطلاب', Student::count())
                ->description('إجمالي الطلاب المسجلين')
                ->descriptionIcon('heroicon-m-users')
                ->color('success'),
                
            Stat::make('طاقم العمل', User::whereIn('role', [User::ROLE_MUHDIR, User::ROLE_MURAQIB])->count())
                ->description('محضرين ومراقبين')
                ->descriptionIcon('heroicon-m-user-group')
                ->color('info'),
                
            Stat::make('الجامعات', University::count())
                ->description('الجامعات المدعومة')
                ->descriptionIcon('heroicon-m-academic-cap')
                ->color('primary'),
                
            Stat::make('إجمالي الاشتراكات', Subscription::where('is_paid', true)->sum('amount') . ' ريال')
                ->description('المحصل الكلي')
                ->descriptionIcon('heroicon-m-banknotes')
                ->color('success'),
                
            Stat::make("اشتراكات الشهر الحالي ($currentArabicName)", $currentMonthAmount . ' ريال')
                ->description('المحصل لهذا الشهر')
                ->descriptionIcon('heroicon-m-currency-dollar')
                ->color('warning'),

            Stat::make("اشتراكات الشهر السابق ($prevArabicName)", $prevMonthAmount . ' ريال')
                ->description('المحصل للشهر الماضي')
                ->descriptionIcon('heroicon-m-currency-dollar')
                ->color('warning'),
        ];
    }
}
