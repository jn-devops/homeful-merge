<?php

namespace App\Filament\Resources\TemplateResource\Pages;

use App\Filament\Resources\TemplateResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;
class CreateTemplate extends CreateRecord
{
    protected static string $resource = TemplateResource::class;
    protected function handleRecordCreation(array $data): Model
    {
        $model =static::getModel()::create($data);
        $model->document = $model->url;
        $model->fields = $data['fields']??[];
        $model->data = $data['data']??[];
        return $model;
    }
}
