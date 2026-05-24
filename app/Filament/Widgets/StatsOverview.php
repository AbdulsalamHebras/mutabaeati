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
        $currentMonth = Carbon::now();
        $prevMonth = Carbon::now()->subMonth();

        $monthIds = Month::where(function ($q) use ($currentMonth) {
            $q->where('name', $currentMonth->format('F'))
              ->where('year', $currentMonth->year);
        })->orWhere(function ($q) use ($prevMonth) {
            $q->where('name', $prevMonth->format('F'))
              ->where('year', $prevMonth->year);
        })->pluck('id');

        $twoMonthsAmount = Subscription::where('is_paid', true)
            ->whereIn('month_id', $monthIds)
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
                
            Stat::make('اشتراكات آخر شهرين', $twoMonthsAmount . ' ريال')
                ->description('المحصل للشهر الحالي والسابق')
                ->descriptionIcon('heroicon-m-currency-dollar')
                ->color('warning'),
        ];
    }
}
