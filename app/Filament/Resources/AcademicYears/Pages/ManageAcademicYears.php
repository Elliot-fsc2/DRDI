<?php

namespace App\Filament\Resources\AcademicYears\Pages;

use App\Filament\Resources\AcademicYears\AcademicYearResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ManageRecords;

class ManageAcademicYears extends ManageRecords
{
    protected static string $resource = AcademicYearResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
                ->createAnother(false)
                ->mutateFormDataUsing(function (array $data): array {
                    // If the new academic year is set to active, deactivate all other academic years
                    if ($data['is_active']) {
                        \App\Models\AcademicYear::query()->update(['is_active' => false]);
                    }
                    $data['year'] = trim($data['year']);

                    return $data;
                }),
        ];
    }
}
