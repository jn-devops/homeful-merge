<?php

namespace App\Filament\Resources\FieldResource\Pages;

use App\Filament\Imports\FieldImporter;
use App\Filament\Resources\FieldResource;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;

class ManageFields extends ManageRecords
{
    protected static string $resource = FieldResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
            Actions\ImportAction::make()
                ->importer(FieldImporter::class)
        ];
    }
}
