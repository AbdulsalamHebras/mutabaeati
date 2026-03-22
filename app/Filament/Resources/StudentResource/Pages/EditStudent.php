<?php

namespace App\Filament\Resources\StudentResource\Pages;

use App\Filament\Resources\StudentResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use App\Models\User;
use App\Notifications\StudentAssignedNotification;

class EditStudent extends EditRecord
{
    protected static string $resource = StudentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    protected $oldMuhdirId;

    protected function beforeSave(): void
    {
        // 🔥 نحفظ المحضر القديم قبل التعديل
        $this->oldMuhdirId = $this->record->muhdir_id;
    }

    protected function afterSave(): void
    {
        $student = $this->record;

        // إذا تغير المحضر
        if ($student->muhdir_id != $this->oldMuhdirId) {

            // ✅ المحضر الجديد
            $newMuhdir = \App\Models\User::find($student->muhdir_id);
            if ($newMuhdir) {
                $newMuhdir->notify(
                    new \App\Notifications\StudentAssignedNotification($student, 'assigned')
                );
            }

            // ✅ المحضر القديم
            $oldMuhdir = \App\Models\User::find($this->oldMuhdirId);
            if ($oldMuhdir) {
                $oldMuhdir->notify(
                    new \App\Notifications\StudentAssignedNotification($student, 'removed')
                );
            }
        }
    }
}
