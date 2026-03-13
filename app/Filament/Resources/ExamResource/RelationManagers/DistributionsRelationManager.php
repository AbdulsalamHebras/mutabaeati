<?php

namespace App\Filament\Resources\ExamResource\RelationManagers;

use App\Models\User;
use App\Models\Student;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class DistributionsRelationManager extends RelationManager
{
    protected static string $relationship = 'distributions';
    
    protected static ?string $title = 'توزيعات الطلاب';
    
    protected static ?string $modelLabel = 'توزيع';
    
    protected static ?string $pluralModelLabel = 'التوزيعات';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('student_id')
                    ->label('الطالب')
                    ->relationship('student', 'name')
                    ->searchable()
                    ->required(),
                Forms\Components\Select::make('supervisor_id')
                    ->label('المشرف')
                    ->relationship('supervisor', 'name')
                    ->searchable()
                    ->required(),
                Forms\Components\TextInput::make('room_number')
                    ->label('رقم القاعة')
                    ->maxLength(255),
                Forms\Components\TextInput::make('seat_number')
                    ->label('رقم الجلوس')
                    ->maxLength(255),
            ])->columns(2);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('seat_number')
            ->columns([
                Tables\Columns\TextColumn::make('student.name')
                    ->label('الطالب')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('supervisor.name')
                    ->label('المشرف')
                    ->sortable(),
                Tables\Columns\TextColumn::make('room_number')
                    ->label('القاعة')
                    ->sortable(),
                Tables\Columns\TextColumn::make('seat_number')
                    ->label('رقم الجلوس')
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('supervisor')
                    ->label('المشرف')
                    ->relationship('supervisor', 'name'),
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()
                    ->label('إضافة توزيع فردي'),
                Tables\Actions\Action::make('bulk_distribution')
                    ->label('توزيع بالجملة')
                    ->icon('heroicon-o-users')
                    ->form([
                        Forms\Components\Select::make('student_ids')
                            ->label('الطلاب')
                            ->multiple()
                            ->options(function (RelationManager $livewire) {
                                $assignedStudentIds = $livewire->getOwnerRecord()->distributions()->pluck('student_id')->toArray();
                                return Student::whereNotIn('id', $assignedStudentIds)->pluck('name', 'id');
                            })
                            ->searchable()
                            ->required(),
                        Forms\Components\Select::make('supervisor_id')
                            ->label('المشرف')
                            ->options(User::pluck('name', 'id'))
                            ->searchable()
                            ->required(),
                        Forms\Components\TextInput::make('room_number')
                            ->label('رقم القاعة')
                            ->maxLength(255),
                    ])
                    ->action(function (array $data, RelationManager $livewire): void {
                        foreach ($data['student_ids'] as $studentId) {
                            $livewire->getOwnerRecord()->distributions()->create([
                                'student_id' => $studentId,
                                'supervisor_id' => $data['supervisor_id'],
                                'room_number' => $data['room_number'] ?? null,
                            ]);
                        }
                    })
                    ->deselectRecordsAfterCompletion(),
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
}
