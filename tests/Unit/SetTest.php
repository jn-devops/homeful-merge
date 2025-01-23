<?php

use Illuminate\Foundation\Testing\{RefreshDatabase, WithFaker};
use App\Models\{Set, Template};

uses(RefreshDatabase::class, WithFaker::class);

test('set model has attributes', function () {
    $template = Set::factory()->create();
    expect($template->code)->toBeString();
    expect($template->name)->toBeString();
});

test('set model has many template models', function () {
    $set = Set::factory()->create();
    expect($set->templates)->toHaveCount(0);
    [$template1, $template2] = Template::factory(2)->create();
    $set->templates()->saveMany([$template1, $template2]);
    $set->refresh();
    expect($set->templates)->toHaveCount(2);
});

