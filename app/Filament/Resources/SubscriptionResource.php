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
                    ->sortable(),
                Tables\Columns\TextColumn::make('university.name')
                    ->label('الجامعة')
                    ->sortable(),
                Tables\Columns\TextColumn::make('batch.name')
                    ->label('الدفعة')
                    ->sortable(),
                Tables\Columns\IconColumn::make('subscriptions_count')
                    ->label('عدد الاشتراكات المسجلة')
                    ->counts('subscriptions')
                    ->icon('heroicon-o-check-circle'),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('university')
                    ->label('الجامعة')
                    ->relationship('university', 'name'),
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
}
