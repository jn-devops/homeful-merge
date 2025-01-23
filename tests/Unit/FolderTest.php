<?php

use Illuminate\Foundation\Testing\{RefreshDatabase, WithFaker};
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use App\Models\{Folder, Set, Template};

uses(RefreshDatabase::class, WithFaker::class);

test('folder model has attributes', function () {
    $folder = Folder::factory()->create();
    expect($folder->id)->toBeUuid();
    expect($folder->code)->toBeString();
    expect($folder->set_code)->toBeString();
    expect($folder->data)->toBeArray();
});

test('folder model can set set_code and data attributes', function () {
    $folder = Folder::factory()->create(['set_code' => null, 'data' => null]);
    expect($folder->set_code)->toBeNull();
    expect($folder->data)->toBeNull();
    $set_code = fake()->word();
    $data = fake()->rgbColorAsArray();
    $folder->update(compact('set_code', 'data'));
    expect($folder->set_code)->toBe($set_code);
    expect($folder->data)->toBe($data);
});

dataset('template_1', function () {
    return [
        [fn() => with(Template::factory()->create(), function (Template $template) {
            $template->document = 'https://unec.edu.az/application/uploads/2014/12/pdf-sample.pdf';
            $template->refresh();

            return $template;
        })]
    ];
});

dataset('template_2', function () {
    return [
        [fn() => with(Template::factory()->create(), function (Template $template) {
            $template->document = 'https://s29.q4cdn.com/175625835/files/doc_downloads/test.pdf';
            $template->refresh();

            return $template;
        })]
    ];
});

test('folder model adds template document path', function (Template $template_1, Template $template_2) {
    $folder = Folder::factory()->create();
    expect($folder->getMedia())->toHaveCount(0);
    $folder->addDocument($template_1->document->getPath());
    $folder->refresh();
    expect($folder->getDocuments())->toHaveCount(1);
    $folder->addDocument($template_2->document->getPath());
    $folder->refresh();
    expect($folder->getDocuments())->toHaveCount(2);
    $array = [];
    $folder->getDocuments()->each(function (Media $media) use (&$array) {
        $array [] = $media->getPath();
    });
    expect(file_exists($array[0]))->toBeTrue();
    expect(file_exists($array[1]))->toBeTrue();
    $folder->clearMediaCollection(Folder::COLLECTION_NAME);
})->with('template_1', 'template_2');

//TODO: generate the files before adding to the folder
