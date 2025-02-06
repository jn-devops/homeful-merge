<?php

namespace App\Filament\Resources\TemplateResource\Pages;

use App\Filament\Imports\FieldImporter;
use App\Filament\Imports\TemplateImporter;
use App\Filament\Resources\TemplateResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListTemplates extends ListRecords
{
    protected static string $resource = TemplateResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
            Actions\ImportAction::make()
                ->importer(TemplateImporter::class)
        ];
    }
}
