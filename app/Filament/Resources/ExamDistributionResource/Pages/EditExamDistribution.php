<?php

namespace App\Filament\Resources\ExamDistributionResource\Pages;

use App\Filament\Resources\ExamDistributionResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditExamDistribution extends EditRecord
{
    protected static string $resource = ExamDistributionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
