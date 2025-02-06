<?php

namespace App\Filament\Imports;

use App\Models\Field;
use App\Models\FieldTemplate;
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
                ->array(',')
                ->ignoreBlankState()
                ->fillRecordUsing(function (Template $record, array $state): void {
                    $existingFields = Field::whereIn('name', $state)->pluck('id', 'name')->toArray();
                    $missingFields = array_diff($state, array_keys($existingFields));
                    foreach ($missingFields as $name) {
                        $existingFields[$name] = Field::updateOrCreate(['name' => $name,'type'=>'String'])->id;
                    }
                    foreach ($existingFields as $name => $id) {
                        FieldTemplate::updateOrCreate(['template_id' => $record->id,'field_id' => $id]);
                    }

                    $record->document = $record->url;
                    $record->data = $record->data??"[]";
                    $record->save();
                })
                ->rules(['required']),
//            ImportColumn::make('data'),
        ];
    }

    public function resolveRecord(): ?Template
    {

        $template = Template::firstOrNew([
            'code' => $this->data['code'],
            'name' => $this->data['name'],
            'url' => $this->data['url'],
        ]);

        $template->save();
        // Check if fields data is available
        if (!empty($this->data['fields'])) {
            $existingFields = Field::whereIn('name', $this->data['fields'])->pluck('id', 'name')->toArray();
            $missingFields = array_diff($this->data['fields'], array_keys($existingFields));
            foreach ($missingFields as $name) {
                $existingFields[$name] = Field::updateOrCreate(['name' => $name,'type'=>'String'])->id;
            }
            foreach ($existingFields as $name => $id) {
                FieldTemplate::updateOrCreate(['template_id' => $template->id,'field_id' => $id]);
            }
        }

        $template->document = $template->url;
        $template->data = $template->data??"[]";
        $template->save();

        return $template;
//        return new Template();
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
