<?php

use Illuminate\Foundation\Testing\{RefreshDatabase, WithFaker};
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Illuminate\Support\Facades\Event;
use App\Events\TemplateDownloaded;
use App\Actions\DownloadTemplate;
use App\Models\Template;
use App\Models\User;

uses(RefreshDatabase::class, WithFaker::class);

test('download template action works', function () {
    Event::fake([TemplateDownloaded::class]);
    $template = Template::factory()->create();
    $url = 'not valid url';
    $response = DownloadTemplate::run($template, $url);
    expect($response)->toBeFalse();
    $url = 'https://unec.edu.az/application/uploads/2014/12/pdf-sample.pdf';
    expect($template->document)->toBeNull();
    $template = DownloadTemplate::run($template, $url);
    expect($template)->toBeInstanceOf(Template::class);
    expect($template->document)->toBeInstanceOf(Media::class);
    expect($template->document->file_name)->toBe('pdf-sample.pdf');
    expect($template->document->name)->toBe('document');
    Event::assertDispatched(TemplateDownloaded::class, function ($event) use ($template) {
        return $event->template == $template;
    });
    $template->document->delete();
    $template->clearMediaCollection(Template::COLLECTION_NAME);
});

test('download template has end point', function () {
    $user = User::factory()->create();
    $template = Template::factory()->create();
    $url = 'https://unec.edu.az/application/uploads/2014/12/pdf-sample.pdf';
    $response = $this->actingAs($user)
        ->post(route('download-template', ['template' => $template]), ['url' => $url]);
    expect($response->status())->toBe(302);
});
