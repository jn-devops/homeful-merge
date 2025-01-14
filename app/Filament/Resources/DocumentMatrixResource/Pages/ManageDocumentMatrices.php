<?php

namespace App\Filament\Resources\DocumentMatrixResource\Pages;

use App\Filament\Resources\DocumentMatrixResource;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;

class ManageDocumentMatrices extends ManageRecords
{
    protected static string $resource = DocumentMatrixResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
