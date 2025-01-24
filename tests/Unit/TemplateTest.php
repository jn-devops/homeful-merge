<?php

use Illuminate\Foundation\Testing\{RefreshDatabase, WithFaker};
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use App\Models\{Field, Template};

uses(RefreshDatabase::class, WithFaker::class);

test('template model has attributes', function () {
    $template = Template::factory()->create();
    expect($template->code)->toBeString();
    expect($template->name)->toBeString();
    expect($template->url)->toBeString();
    expect($template->data)->toBeString();
});

test('template model has many field models', function () {
    $template = Template::factory()->create();
    expect($template->fields)->toHaveCount(0);
    [$field1, $field2] = Field::factory(2)->create();
    $template->fields()->saveMany([$field1, $field2]);
    $template->refresh();
    expect($template->fields)->toHaveCount(2);
});

test('template model has a document attribute', function () {
    $template = Template::factory()->create();
    expect($template->document)->toBeNull();
    $template->document = 'https://unec.edu.az/application/uploads/2014/12/pdf-sample.pdf';
    $template->refresh();
    expect($template->document)->toBeInstanceOf(Media::class);
    expect($template->document->file_name)->toBe('pdf-sample.pdf');
    expect($template->document->name)->toBe('document');
    expect($template->document->getUrl())->toBeUrl();
    $template->document->delete();
    $template->clearMediaCollection(Template::COLLECTION_NAME);
});

