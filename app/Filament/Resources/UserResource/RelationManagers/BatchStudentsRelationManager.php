<?php

namespace App\Filament\Resources\UserResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;

class BatchStudentsRelationManager extends RelationManager
{
    protected static string $relationship = 'batchStudents';

    protected static ?string $title = 'طلاب الدفعة المكلف بها';

    protected static ?string $modelLabel = 'طالب';

    protected static ?string $pluralModelLabel = 'طلاب الدفعة';

    public static function canViewForRecord(Model $activeRecord, string $pageClass): bool
    {
        return $activeRecord->role === 'muraqib';
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('الاسم')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('university.name')
                    ->label('الجامعة'),
                Tables\Columns\TextColumn::make('batch.name')
                    ->label('الدفعة'),
                Tables\Columns\TextColumn::make('muhdir.name')
                    ->label('المحضر المكلف'),
                Tables\Columns\TextColumn::make('status')
                    ->label('الحالة')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'نشط' => 'success',
                        'مقيد' => 'danger',
                    }),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                //
            ]);
    }
}
