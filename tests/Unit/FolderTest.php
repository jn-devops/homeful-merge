<?php

use Illuminate\Foundation\Testing\{RefreshDatabase, WithFaker};
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use App\Models\{Folder, Set, Template};

uses(RefreshDatabase::class, WithFaker::class);

test('folder model has attributes', function () {
    $folder = Folder::factory()->create();
    expect($folder->id)->toBeUuid();
    expect($folder->code)->toBeString();
});

dataset('document1_path', function () {
    return [
        [fn() => with(Template::factory()->create(), function (Template $template) {
            $template->document = 'https://unec.edu.az/application/uploads/2014/12/pdf-sample.pdf';

            return $template->document->getPath();
        })]
    ];
});

dataset('document2_path', function () {
    return [
        [fn() => with(Template::factory()->create(), function (Template $template) {
            $template->document = 'https://s29.q4cdn.com/175625835/files/doc_downloads/test.pdf';

            return $template->document->getPath();
        })]
    ];
});

test('folder model has a document attribute', function (string $document1_path, string $document2_path) {
    $folder = Folder::factory()->create();
    expect($folder->getMedia())->toHaveCount(0);
    $folder->addDocument($document1_path);
    $folder->refresh();
    expect($folder->getDocuments())->toHaveCount(1);
    $folder->addDocument($document2_path);
    $folder->refresh();
    expect($folder->getDocuments())->toHaveCount(2);
    $array = [];
    $folder->getDocuments()->each(function (Media $media) use (&$array) {
        $array [] = $media->getPath();
    });
    expect(file_exists($array[0]))->toBeTrue();
    expect(file_exists($array[1]))->toBeTrue();
    $folder->clearMediaCollection(Folder::COLLECTION_NAME);
})->with('document1_path', 'document2_path');
