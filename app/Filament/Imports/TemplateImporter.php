<?php

namespace App\Filament\Imports;

use App\Models\Field;
use App\Models\Template;
use Filament\Actions\Imports\ImportColumn;
use Filament\Actions\Imports\Importer;
use Filament\Actions\Imports\Models\Import;

class TemplateImporter extends Importer
{
    protected static ?string $model = Template::class;

    public static function getColumns(): array
    {
        return [
            ImportColumn::make('code')
                ->requiredMapping()
                ->rules(['required', 'max:255']),
            ImportColumn::make('name')
                ->requiredMapping()
                ->rules(['required', 'max:255']),
            ImportColumn::make('url')
                ->requiredMapping()
                ->rules(['required']),
            ImportColumn::make('fields')
                ->requiredMapping()
                ->fillRecordUsing(function (Template $record, string $state): void {
                    $fieldIds = Field::whereIn('name',explode(',',$state) )->pluck('id')->toArray();
                    $record->fields()->sync($fieldIds);
                })
                ->rules(['required']),
//            ImportColumn::make('data'),
        ];
    }

    public function resolveRecord(): ?Template
    {

//        $template = Template::firstOrNew([
//            'code' => $this->data['code'],
//            'name' => $this->data['name'],
//            'url' => $this->data['url'],
//        ]);
//
//        // Ensure the template is saved before adding relations
//        $template->save();
//
//        // Check if fields data is available
//        if (!empty($this->data['fields'])) {
//            $fieldIds = Field::whereIn('name',explode(',',$this->data['fields']) )->pluck('id')->toArray();
//            $template->fields()->sync($fieldIds);
//        }
//
//
//        return $template;
        return new Template();
    }
    protected function beforeSave(): void
    {
//        $template = Template::firstOrNew([
//            'code' => $this->data['code'],
//            'name' => $this->data['name'],
//            'url' => $this->data['url'],
//        ]);
//
//        // Ensure the template is saved before adding relations
//        $template->save();
//
//        // Check if fields data is available
//        if (!empty($this->data['fields'])) {
//            $fieldIds = Field::whereIn('name',explode(',',$this->data['fields']) )->pluck('id')->toArray();
//            $template->fields()->sync($fieldIds);
//        }
    }

    public static function getCompletedNotificationBody(Import $import): string
    {
        $body = 'Your template import has completed and ' . number_format($import->successful_rows) . ' ' . str('row')->plural($import->successful_rows) . ' imported.';

        if ($failedRowsCount = $import->getFailedRowsCount()) {
            $body .= ' ' . number_format($failedRowsCount) . ' ' . str('row')->plural($failedRowsCount) . ' failed to import.';
        }

        return $body;
    }
}
