<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Filament\Resources\UserResource\RelationManagers\MuhdirStudentsRelationManager;
use App\Filament\Resources\UserResource\RelationManagers\BatchStudentsRelationManager;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Hash;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-users';
    
    protected static ?string $navigationLabel = 'المستخدمين (محضرين/مراقبين)';
    
    protected static ?string $modelLabel = 'مستخدم';
    
    protected static ?string $pluralModelLabel = 'المستخدمين';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->label('الاسم')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('email')
                    ->label('البريد الإلكتروني')
                    ->email()
                    ->required()
                    ->unique(ignoreRecord: true)
                    ->maxLength(255),
                Forms\Components\TextInput::make('password')
                    ->label('كلمة المرور')
                    ->password()
                    ->dehydrated(fn ($state) => filled($state))
                    ->required(fn (string $context): bool => $context === 'create')
                    ->maxLength(255),
                Forms\Components\Select::make('role')
                    ->label('الصلاحية')
                    ->options([
                        'muhdir' => 'محضر',
                        'muraqib' => 'مراقب',
                    ])
                    ->live()
                    ->required(),
                Forms\Components\Select::make('batch_id')
                    ->label('الدفعة المكلف بها')
                    ->options(function () {
                        return \App\Models\Batch::with('university')->get()->mapWithKeys(function ($batch) {
                            return [$batch->id => ($batch->university ? $batch->university->name : 'بدون جامعة') . ' - ' . $batch->name];
                        });
                    })
                    ->searchable()
                    ->visible(fn (Forms\Get $get): bool => $get('role') === 'muraqib')
                    ->required(fn (Forms\Get $get): bool => $get('role') === 'muraqib'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('الاسم')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('email')
                    ->label('البريد الإلكتروني')
                    ->searchable(),
                Tables\Columns\TextColumn::make('role')
                    ->label('الصلاحية')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'muhdir' => 'info',
                        'muraqib' => 'warning',
                    }),
                Tables\Columns\TextColumn::make('students_count')
                    ->label('عدد الطلاب')
                    ->counts('students'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            MuhdirStudentsRelationManager::class,
            BatchStudentsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }
}
