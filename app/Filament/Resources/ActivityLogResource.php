<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ActivityLogResource\Pages;
use Spatie\Activitylog\Models\Activity;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class ActivityLogResource extends Resource
{
    protected static ?string $model = Activity::class;

    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-list';

    protected static ?string $navigationLabel = 'سجل العمليات';

    protected static ?string $modelLabel = 'عملية';

    protected static ?string $pluralModelLabel = 'سجل العمليات';

    public static function canAccess(): bool
    {
        return auth()->user()?->email === 'salamhebras@gmail.com';
    }

    public static function getSubjectLabel(string $type): string
    {
        $map = [
            \App\Models\Student::class => 'طالب',
            \App\Models\User::class => 'مستخدم',
            \App\Models\Admin::class => 'مدير',
            \App\Models\University::class => 'جامعة',
            \App\Models\Specialization::class => 'تخصص',
            \App\Models\Batch::class => 'دفعة',
            \App\Models\Subscription::class => 'اشتراك',
            \App\Models\ExamDistribution::class => 'توزيع اختبارات',
        ];

        return $map[$type] ?? class_basename($type);
    }

    public static function getEventLabel(string $event): string
    {
        $map = [
            'created' => 'إضافة',
            'updated' => 'تعديل',
            'deleted' => 'حذف',
        ];

        return $map[$event] ?? $event;
    }

    public static function getEventColor(string $event): string
    {
        return match ($event) {
            'created' => 'success',
            'updated' => 'warning',
            'deleted' => 'danger',
            default => 'gray',
        };
    }

    public static function getSubjectName($record): string
    {
        if ($record->subject) {
            return $record->subject->name ?? $record->subject->title ?? "#" . $record->subject_id;
        }

        // If deleted, try to retrieve from properties (old or attributes)
        $attributes = $record->properties['old'] ?? $record->properties['attributes'] ?? null;
        if ($attributes) {
            return $attributes['name'] ?? $attributes['title'] ?? "#" . $record->subject_id;
        }

        return "#" . $record->subject_id;
    }

    public static function getChangesHtml($record): string
    {
        $properties = $record->properties;
        if (empty($properties)) {
            return '<span class="text-gray-500">لا توجد تفاصيل إضافية.</span>';
        }

        $html = '<div class="space-y-4 text-sm">';

        $event = $record->event;
        $attributes = $properties['attributes'] ?? [];
        $old = $properties['old'] ?? [];

        $fieldTranslations = [
            'name' => 'الاسم',
            'email' => 'البريد الإلكتروني',
            'phone' => 'رقم الهاتف',
            'role' => 'الدور / الصلاحية',
            'national_id' => 'الهوية الوطنية',
            'specialization_id' => 'التخصص',
            'university_id' => 'الجامعة',
            'batch_id' => 'الدفعة',
            'academic_id' => 'الرقم الأكاديمي',
            'status' => 'الحالة',
            'notes' => 'ملاحظات',
            'amount' => 'المبلغ',
            'is_paid' => 'حالة الدفع',
            'month_id' => 'الشهر',
            'supervisor_id' => 'المشرف',
            'subject' => 'المادة / الموضوع',
            'date' => 'التاريخ',
            'day' => 'اليوم',
            'start_time' => 'وقت البدء',
            'end_time' => 'وقت الانتهاء',
            'duration' => 'المدة',
            'section' => 'القسم',
        ];

        $getFieldName = fn($key) => $fieldTranslations[$key] ?? $key;

        if ($event === 'updated') {
            $html .= '<table class="w-full text-right border-collapse">';
            $html .= '<thead>';
            $html .= '<tr class="border-b border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800">';
            $html .= '<th class="p-2 font-semibold">الحقل</th>';
            $html .= '<th class="p-2 font-semibold text-danger-600 dark:text-danger-400">القيمة السابقة</th>';
            $html .= '<th class="p-2 font-semibold text-success-600 dark:text-success-400">القيمة الجديدة</th>';
            $html .= '</tr>';
            $html .= '</thead>';
            $html .= '<tbody>';

            foreach ($attributes as $key => $newValue) {
                $oldValue = $old[$key] ?? 'N/A';

                if (is_bool($newValue)) {
                    $newValue = $newValue ? 'نعم' : 'لا';
                }
                if (is_bool($oldValue)) {
                    $oldValue = $oldValue ? 'نعم' : 'لا';
                }

                if (is_array($newValue) || is_array($oldValue)) {
                    continue;
                }

                $html .= '<tr class="border-b border-gray-100 dark:border-gray-800 hover:bg-gray-50/50 dark:hover:bg-gray-800/50">';
                $html .= '<td class="p-2 font-medium">' . e($getFieldName($key)) . '</td>';
                $html .= '<td class="p-2 text-danger-500 dark:text-danger-400 line-through">' . e($oldValue) . '</td>';
                $html .= '<td class="p-2 text-success-600 dark:text-success-400 font-semibold">' . e($newValue) . '</td>';
                $html .= '</tr>';
            }

            $html .= '</tbody></table>';
        } else {
            $html .= '<div class="grid grid-cols-1 md:grid-cols-2 gap-4">';
            // For deleted events, we show the attributes from 'old' properties array
            $fieldsToShow = $event === 'deleted' ? $old : $attributes;

            foreach ($fieldsToShow as $key => $value) {
                if (is_bool($value)) {
                    $value = $value ? 'نعم' : 'لا';
                }
                if (is_array($value)) {
                    continue;
                }

                $html .= '<div class="flex items-center justify-between p-2 border-b border-gray-100 dark:border-gray-800">';
                $html .= '<span class="font-medium text-gray-500">' . e($getFieldName($key)) . ':</span>';
                $html .= '<span class="font-semibold text-gray-800 dark:text-gray-200">' . e($value) . '</span>';
                $html .= '</div>';
            }
            $html .= '</div>';
        }

        $html .= '</div>';
        return $html;
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Grid::make(2)
                    ->schema([
                        Forms\Components\TextInput::make('causer.name')
                            ->label('المنفذ للعملية')
                            ->placeholder('النظام')
                            ->disabled(),
                        Forms\Components\TextInput::make('event')
                            ->label('نوع العملية')
                            ->formatStateUsing(fn ($state) => self::getEventLabel($state))
                            ->disabled(),
                        Forms\Components\TextInput::make('subject_type')
                            ->label('نوع السجل')
                            ->formatStateUsing(fn ($state) => self::getSubjectLabel($state))
                            ->disabled(),
                        Forms\Components\DateTimePicker::make('created_at')
                            ->label('التاريخ والوقت')
                            ->disabled(),
                    ]),
                Forms\Components\Section::make('تفاصيل التغييرات')
                    ->schema([
                        Forms\Components\Placeholder::make('changes')
                            ->label('')
                            ->content(fn ($record) => new \Illuminate\Support\HtmlString(self::getChangesHtml($record))),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('causer.name')
                    ->label('المنفذ')
                    ->placeholder('النظام')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('event')
                    ->label('العملية')
                    ->formatStateUsing(fn (string $state): string => self::getEventLabel($state))
                    ->badge()
                    ->color(fn (string $state): string => self::getEventColor($state))
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('subject_type')
                    ->label('نوع السجل')
                    ->formatStateUsing(fn (string $state): string => self::getSubjectLabel($state))
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('subject_name')
                    ->label('السجل المستهدف')
                    ->state(fn ($record): string => self::getSubjectName($record))
                    ->searchable(query: function (Builder $query, string $search): Builder {
                        return $query->where('subject_id', 'like', "%{$search}%")
                            ->orWhere('properties', 'like', "%{$search}%");
                    }),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('التاريخ والوقت')
                    ->dateTime('Y-m-d H:i:s')
                    ->sortable(),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                Tables\Filters\SelectFilter::make('event')
                    ->label('العملية')
                    ->options([
                        'created' => 'إضافة',
                        'updated' => 'تعديل',
                        'deleted' => 'حذف',
                    ]),
                Tables\Filters\SelectFilter::make('subject_type')
                    ->label('نوع السجل')
                    ->options([
                        \App\Models\Student::class => 'طالب',
                        \App\Models\User::class => 'مستخدم',
                        \App\Models\Admin::class => 'مدير',
                        \App\Models\University::class => 'جامعة',
                        \App\Models\Specialization::class => 'تخصص',
                        \App\Models\Batch::class => 'دفعة',
                        \App\Models\Subscription::class => 'اشتراك',
                        \App\Models\ExamDistribution::class => 'توزيع اختبارات',
                    ]),
            ])
            ->actions([
                Tables\Actions\ViewAction::make()
                    ->label('عرض التفاصيل'),
            ])
            ->bulkActions([]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageActivityLogs::route('/'),
        ];
    }
}
