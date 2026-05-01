<?php

namespace App\Filament\Resources;

use App\Filament\Resources\StudentResource\Pages;
use App\Filament\Resources\StudentResource\RelationManagers;
use App\Filament\Resources\StudentResource\Pages\CreateStudent;
use App\Filament\Resources\StudentResource\Pages\EditStudent;
use App\Models\Student;
use App\Models\Batch;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Collection;

class StudentResource extends Resource
{
    protected static ?string $model = Student::class;

    protected static ?string $navigationIcon = 'heroicon-o-user-group';

    protected static ?string $navigationLabel = 'الطلاب';

    protected static ?string $modelLabel = 'طالب';

    protected static ?string $pluralModelLabel = 'الطلاب';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('المعلومات الأساسية')
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->label('اسم الطالب')
                            ->required()
                            ->maxLength(255),
                        // [NEW] Added platform_password
                        Forms\Components\TextInput::make('platform_password')
                            ->label('كلمة المرور للمنصة')
                            ->maxLength(255),
                        Forms\Components\TextInput::make('academic_id')
                            ->label('الرقم الأكاديمي')
                            ->maxLength(255),
                        Forms\Components\TextInput::make('national_id')
                            ->label('رقم الهوية')
                            ->unique(ignoreRecord: true)
                            ->maxLength(255),
                    ])->columns(2),

                Forms\Components\Section::make('معلومات التواصل')
                    ->schema([
                        Forms\Components\TextInput::make('phone')
                            ->label('رقم الجوال')
                            ->tel()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('email')
                            ->label('البريد الإلكتروني')
                            ->email()
                            ->unique(ignoreRecord: true)
                            ->maxLength(255),
                    ])->columns(2),

                Forms\Components\Section::make('المعلومات الأكاديمية')
                    ->schema([
                        Forms\Components\Select::make('university_id')
                            ->label('الجامعة')
                            ->relationship('university', 'name')
                            ->searchable()
                            ->preload()
                            ->live()
                            ->afterStateUpdated(fn (Forms\Set $set) => $set('batch_id', null))
                            ->required(),
                        Forms\Components\Select::make('batch_id')
                            ->label('الدفعة')
                            ->options(fn (Forms\Get $get): Collection => Batch::query()
                                ->where('university_id', $get('university_id'))
                                ->pluck('name', 'id'))
                            ->searchable()
                            ->preload()
                            ->disabled(fn (Forms\Get $get): bool => ! $get('university_id'))
                            ->required(),
                        Forms\Components\Select::make('specialization_id')
                            ->label('التخصص')
                            ->relationship('specialization', 'name')
                            ->searchable()
                            ->preload()
                            ->required(),
                        Forms\Components\Select::make('duration')
                            ->label('مدة الدراسة')
                            ->options([
                                'عام' => 'عام',
                                'عامين' => 'عامين',
                            ])
                            ->required(),
                        Forms\Components\TextInput::make('section')
                            ->label('الشعبة')
                            ->maxLength(255),
                    ])->columns(3),

                Forms\Components\Section::make('التكليف والحالة')
                    ->schema([
                        Forms\Components\Select::make('muhdir_id')
                            ->label('المحضر المكلف')
                            ->relationship('muhdir', 'name', fn ($query) => $query->where('role', 'muhdir'))
                            ->searchable()
                            ->preload()
                            ->required()
                            ->visible(fn () => in_array(auth()->user()?->email, [
                                'abeer@gmail.com',
                                'muetamir@gmail.com',
                                'salamhebras@gmail.com',
                            ])),
                        Forms\Components\Select::make('status')
                            ->label('الحالة')
                            ->options([
                                'نشط' => 'نشط',
                                'مقيد' => 'مقيد',
                            ])
                            ->required(),
                    ])->columns(2),

                Forms\Components\Section::make('ملاحظات إضافية')
                    ->schema([
                        Forms\Components\Textarea::make('notes')
                            ->label('ملاحظة')
                            ->columnSpanFull(),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('الاسم')
                    ->searchable()
                    ->sortable()
                    ->copyable()
                    ->copyMessage('تم نسخ الاسم'),
                Tables\Columns\TextColumn::make('phone')
                    ->label('رقم الجوال')
                    ->searchable()
                    ->copyable()
                    ->copyMessage('تم نسخ رقم الجوال'),
                Tables\Columns\TextColumn::make('national_id')
                    ->label('رقم الهوية')
                    ->searchable()
                    ->copyable()
                    ->copyMessage('تم نسخ رقم الهوية'),
                Tables\Columns\TextColumn::make('university.name')
                    ->label('الجامعة')
                    ->sortable(),
                Tables\Columns\TextColumn::make('batch.name')
                    ->label('الدفعة')
                    ->sortable(),
                Tables\Columns\TextColumn::make('specialization.name')
                    ->label('التخصص')
                    ->sortable(),
                Tables\Columns\TextColumn::make('muhdir.name')
                    ->label('المحضر')
                    ->sortable()
                    ->visible(fn () => in_array(auth()->user()?->email, [
                        'abeer@gmail.com',
                        'muetamir@gmail.com',
                        'salamhebras@gmail.com',
                    ])),
                Tables\Columns\TextColumn::make('admin.name')
                    ->label('المسؤول')
                    ->sortable(), 
                Tables\Columns\TextColumn::make('status')
                    ->label('الحالة')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'نشط' => 'success',
                        'مقيد' => 'danger',
                    }),
                Tables\Columns\TextColumn::make('notes')
                    ->label('ملاحظة')
                    ->limit(50)
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('admin')
                    ->label('المسؤول')
                    ->relationship('admin', 'name'),
                Tables\Filters\SelectFilter::make('university')
                    ->label('الجامعة')
                    ->relationship('university', 'name'),
                Tables\Filters\SelectFilter::make('batch')
                    ->label('الدفعة')
                    ->relationship('batch', 'name'),        
                Tables\Filters\SelectFilter::make('muhdir')
                    ->label('المحضر')
                    ->relationship('muhdir', 'name', fn ($query) => $query->where('role', 'muhdir'))
                    ->visible(fn () => in_array(auth()->user()?->email, [
                        'abeer@gmail.com',
                        'muetamir@gmail.com',
                        'salamhebras@gmail.com',
                    ])),
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
            RelationManagers\SubscriptionsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListStudents::route('/'),
            'create' => Pages\CreateStudent::route('/create'),
            'edit' => Pages\EditStudent::route('/{record}/edit'),
        ];
    }
}
