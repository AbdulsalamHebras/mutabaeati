<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SubscriptionResource\Pages;
use App\Models\Student;
use App\Models\Month;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class SubscriptionResource extends Resource
{
    protected static ?string $model = Student::class;

    protected static ?string $navigationIcon = 'heroicon-o-credit-card';
    
    protected static ?string $navigationLabel = 'الاشتراكات';
    
    protected static ?string $modelLabel = 'اشتراك طالب';
    
    protected static ?string $pluralModelLabel = 'اشتراكات الطلاب';

    protected static ?string $slug = 'student-subscriptions';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('بيانات الطالب')
                    ->description('إدارة اشتراكات الطالب الشهرية')
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->label('اسم الطالب')
                            ->disabled(),
                        Forms\Components\Repeater::make('subscriptions')
                            ->label('سجل الاشتراكات')
                            ->relationship()
                            ->schema([
                                Forms\Components\Select::make('month_id')
                                    ->label('الشهر')
                                    ->options(Month::all()->mapWithKeys(function ($month) {
                                        return [$month->id => "{$month->name} {$month->year}"];
                                    }))
                                    ->required()
                                    ->searchable(),
                                Forms\Components\TextInput::make('amount')
                                    ->label('المبلغ')
                                    ->numeric()
                                    ->default(0)
                                    ->required(),
                                Forms\Components\Toggle::make('is_paid')
                                    ->label('تم الدفع')
                                    ->onColor('success')
                                    ->offColor('danger'),
                                Forms\Components\TextInput::make('notes')
                                    ->label('ملاحظات'),
                            ])
                            ->columns(4)
                            ->itemLabel(fn (array $state): ?string => Month::find($state['month_id'] ?? null)?->name ?? 'شهر جديد')
                            ->collapsible()
                            ->defaultItems(0),
                    ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('اسم الطالب')
                    ->searchable()
                    ->sortable()
                    ->copyable()
                    ->copyMessage('تم نسخ الاسم'),
                Tables\Columns\TextColumn::make('university.name')
                    ->label('الجامعة')
                    ->sortable(),
                Tables\Columns\TextColumn::make('national_id')
                    ->label('رقم الهوية')
                    ->searchable()
                    ->sortable()
                    ->copyable()
                    ->copyMessage('تم نسخ رقم الهوية'),
                
                Tables\Columns\IconColumn::make('subscriptions_count')
                    ->label('عدد الاشتراكات المسجلة')
                    ->counts('subscriptions')
                    ->icon('heroicon-o-check-circle'),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('university')
                    ->label('الجامعة')
                    ->relationship('university', 'name'),
                Tables\Filters\Filter::make('subscription_filter')
                    ->form([
                        Forms\Components\Select::make('month_id')
                            ->label('الشهر')
                            ->options(fn () => Month::all()->mapWithKeys(fn ($month) => [$month->id => "{$month->name} {$month->year}"])->toArray())
                            ->searchable(),
                        Forms\Components\Select::make('is_paid')
                            ->label('حالة الدفع')
                            ->options([
                                '1' => 'مدفوع',
                                '0' => 'غير مدفوع',
                            ]),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        $monthId = $data['month_id'] ?? null;
                        $isPaid = $data['is_paid'] ?? null;

                        if (! empty($monthId)) {
                            if ($isPaid === '1') {
                                $query->whereHas('subscriptions', function (Builder $q) use ($monthId) {
                                    $q->where('month_id', $monthId)->where('is_paid', true);
                                });
                            } elseif ($isPaid === '0') {
                                $query->whereDoesntHave('subscriptions', function (Builder $q) use ($monthId) {
                                    $q->where('month_id', $monthId)->where('is_paid', true);
                                });
                            } else {
                                $query->whereHas('subscriptions', function (Builder $q) use ($monthId) {
                                    $q->where('month_id', $monthId);
                                });
                            }
                        } else {
                            if ($isPaid === '1') {
                                $query->whereHas('subscriptions', function (Builder $q) {
                                    $q->where('is_paid', true);
                                });
                            } elseif ($isPaid === '0') {
                                $query->whereDoesntHave('subscriptions', function (Builder $q) {
                                    $q->where('is_paid', true);
                                });
                            }
                        }

                        return $query;
                    })
                    ->indicateUsing(function (array $data): array {
                        $indicators = [];
                        if (! empty($data['month_id'])) {
                            $month = Month::find($data['month_id']);
                            if ($month) {
                                $indicators[] = Tables\Filters\Indicator::make('الشهر: ' . $month->name . ' ' . $month->year)
                                    ->removeField('month_id');
                            }
                        }
                        if ($data['is_paid'] !== null) {
                            $status = $data['is_paid'] === '1' ? 'مدفوع' : 'غير مدفوع';
                            $indicators[] = Tables\Filters\Indicator::make('حالة الدفع: ' . $status)
                                ->removeField('is_paid');
                        }
                        return $indicators;
                    }),
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->label('إدارة الاشتراكات'),
            ])
            ->bulkActions([
                //
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListSubscriptions::route('/'),
            'edit' => Pages\EditSubscription::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        $query = parent::getEloquentQuery();
        
        if ($user = auth()->user()) {
            $query->where('admin_id', $user->id);
        }

        return $query;
    }
}
