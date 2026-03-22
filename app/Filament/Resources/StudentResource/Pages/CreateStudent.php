<?php

namespace App\Filament\Resources\StudentResource\Pages;

use App\Filament\Resources\StudentResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use App\Models\User;
use App\Notifications\StudentAssignedNotification;

class CreateStudent extends CreateRecord
{
    protected static string $resource = StudentResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['admin_id'] = auth()->id();

        return $data;
    }


    protected function afterCreate(): void
    {
        $student = $this->record;

        $muhdir = \App\Models\User::find($student->muhdir_id);

        if ($muhdir) {
            $muhdir->notify(
                new \App\Notifications\StudentAssignedNotification($student, 'assigned')
            );
        }
    }
}
