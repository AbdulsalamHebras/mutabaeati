<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ExamDistributionResource\Pages;
use App\Models\ExamDistribution;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Forms\Set;
use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\Builder;

class ExamDistributionResource extends Resource
{
    protected static ?string $model = ExamDistribution::class;

    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-check';

    protected static ?string $navigationLabel = 'توزيعات الاختبارات';

    protected static ?string $modelLabel = 'توزيع اختبار';

    protected static ?string $pluralModelLabel = 'توزيعات الاختبارات';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('student_id')
                    ->label('الطالب')
                    ->relationship('student', 'name')
                    ->searchable()
                    ->required()
                    ->preload(),
                Forms\Components\Select::make('supervisor_id')
                    ->label('المكلف بالاختبار')
                    ->relationship('supervisor', 'name')
                    ->searchable()
                    ->required()
                    ->preload(),
                Forms\Components\TimePicker::make('start_time')
                    ->label('وقت البداية')
                    ->displayFormat('h:i A')
                    ->seconds(false)
                    ->required(),
                Forms\Components\TimePicker::make('end_time')
                    ->label('وقت النهاية')
                    ->displayFormat('h:i A')
                    ->seconds(false)
                    ->after('start_time')
                    ->required(),
                Forms\Components\TextInput::make('subject')
                    ->label('المادة')
                    ->maxLength(255),
                Forms\Components\DatePicker::make('date')
                    ->label('تاريخ الاختبار')
                    ->required()
                    ->live()
                    ->afterStateUpdated(fn (Set $set, $state) => $set('day', $state ? Carbon::parse($state)->translatedFormat('l') : null)),
                Forms\Components\TextInput::make('day')
                    ->label('اليوم')
                    ->required()
                    ->readOnly(),
            ])->columns(2);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('student.name')
                    ->label('الطالب')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('student.phone')
                    ->label('رقم الجوال')
                    ->searchable(),
                Tables\Columns\TextColumn::make('student.national_id')
                    ->label('رقم الهوية')
                    ->searchable(),
                Tables\Columns\TextColumn::make('supervisor.name')
                    ->label('المكلف بالاختبار')
                    ->sortable(),
                Tables\Columns\TextColumn::make('start_time')
                    ->label('وقت البداية')
                    ->time('h:i A')
                    ->sortable(),
                Tables\Columns\TextColumn::make('end_time')
                    ->label('وقت النهاية')
                    ->time('h:i A')
                    ->sortable(),
                Tables\Columns\TextColumn::make('subject')
                    ->label('المادة')
                    ->sortable(),
                Tables\Columns\TextColumn::make('day')
                    ->label('اليوم')
                    ->sortable(),
                Tables\Columns\TextColumn::make('date')
                    ->label('التاريخ')
                    ->date()
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('supervisor')
                    ->label('المكلف بالاختبار')
                    ->relationship('supervisor', 'name'),
                Tables\Filters\Filter::make('time_range')
                    ->form([
                        Forms\Components\TimePicker::make('start_time')
                            ->label('وقت البداية')
                            ->seconds(false),
                        Forms\Components\TimePicker::make('end_time')
                            ->label('وقت النهاية')
                            ->seconds(false),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['start_time'],
                                fn (Builder $query, $time): Builder => $query->where('start_time', '>=', $time),
                            )
                            ->when(
                                $data['end_time'],
                                fn (Builder $query, $time): Builder => $query->where('end_time', '<=', $time),
                            );
                    }),
                Tables\Filters\Filter::make('date')
                    ->form([
                        Forms\Components\DatePicker::make('date')->label('تاريخ الاختبار'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query->when(
                            $data['date'],
                            fn (Builder $query, $date): Builder => $query->whereDate('date', $date),
                        );
                    })
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
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
            'index' => Pages\ListExamDistributions::route('/'),
            'create' => Pages\CreateExamDistribution::route('/create'),
            'edit' => Pages\EditExamDistribution::route('/{record}/edit'),
        ];
    }
}
