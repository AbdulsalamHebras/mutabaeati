<?php

namespace App\Filament\Resources;

use App\Filament\Resources\BatchResource\Pages;
use App\Models\Batch;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class BatchResource extends Resource
{
    protected static ?string $model = Batch::class;

    protected static ?string $navigationIcon = 'heroicon-o-hashtag';
    
    protected static ?string $navigationLabel = 'الدفعات';
    
    protected static ?string $modelLabel = 'دفعة';
    
    protected static ?string $pluralModelLabel = 'الدفعات';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->label('اسم الدفعة')
                    ->required()
                    ->maxLength(255),
                Forms\Components\Select::make('university_id')
                    ->label('الجامعة')
                    ->relationship('university', 'name')
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('الدفعة')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('university.name')
                    ->label('الجامعة')
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('university')
                    ->relationship('university', 'name'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListBatches::route('/'),
            'create' => Pages\CreateBatch::route('/create'),
            'edit' => Pages\EditBatch::route('/{record}/edit'),
        ];
    }
}
