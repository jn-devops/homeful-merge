<?php

use Illuminate\Foundation\Testing\{RefreshDatabase, WithFaker};
use App\Models\{Field, Template};

uses(RefreshDatabase::class, WithFaker::class);

test('field model has attributes', function () {
    $field = Field::factory()->create();
    expect($field->name)->toBeString();
    expect($field->type)->toBeString();
});

test('template model has many field models', function () {
    $field = Field::factory()->create();
    expect($field->templates)->toHaveCount(0);
    [$template1, $template2] = Template::factory(2)->create();
    $field->templates()->saveMany([$template1, $template2]);
    $field->refresh();
    expect($field->templates)->toHaveCount(2);
});
