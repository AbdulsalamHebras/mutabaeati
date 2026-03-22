<?php

namespace App\Filament\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use App\Models\Student;
use App\Models\User;
use App\Models\University;
use App\Models\Subscription;
use Carbon\Carbon;

class StatsOverview extends BaseWidget
{
    protected function getStats(): array
    {
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
                
            Stat::make('اشتراكات الشهر الحالي', Subscription::where('is_paid', true)
                ->whereMonth('created_at', Carbon::now()->month)
                ->whereYear('created_at', Carbon::now()->year)
                ->sum('amount') . ' ريال')
                ->description('المحصل هذا الشهر')
                ->descriptionIcon('heroicon-m-currency-dollar')
                ->color('warning'),
        ];
    }
}
