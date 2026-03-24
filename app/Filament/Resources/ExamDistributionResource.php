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
                    ->required(),
                Forms\Components\Select::make('supervisor_id')
                    ->label('المكلف بالاختبار')
                    ->relationship('supervisor', 'name')
                    ->searchable()
                    ->required(),
                Forms\Components\Select::make('period')
                    ->label('فترة الاختبار')
                    ->options([
                        'من 4 الى 5' => 'من 4 الى 5',
                        'من 5 الى 6' => 'من 5 الى 6',
                        'من 6 الى 7' => 'من 6 الى 7',
                        'من 7 الى 8' => 'من 7 الى 8',
                        'من 8 الى 9' => 'من 8 الى 9',
                        'من 9 الى 10' => 'من 9 الى 10',
                    ])
                    ->required(),
                Forms\Components\TextInput::make('subject')
                    ->label('المادة')
                    ->maxLength(255),

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
                Tables\Columns\TextColumn::make('period')
                    ->label('الفترة')
                    ->sortable(),
                Tables\Columns\TextColumn::make('subject')
                    ->label('المادة')
                    ->sortable(),

            ])
            ->filters([
                Tables\Filters\SelectFilter::make('supervisor')
                    ->label('المكلف بالاختبار')
                    ->relationship('supervisor', 'name'),
                Tables\Filters\SelectFilter::make('period')
                    ->label('الفترة')
                    ->options([
                        'من 4 الى 5' => 'من 4 الى 5',
                        'من 5 الى 6' => 'من 5 الى 6',
                        'من 6 الى 7' => 'من 6 الى 7',
                        'من 7 الى 8' => 'من 7 الى 8',
                        'من 8 الى 9' => 'من 8 الى 9',
                        'من 9 الى 10' => 'من 9 الى 10',
                    ]),
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
