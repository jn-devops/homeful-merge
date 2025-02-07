<?php

use Illuminate\Foundation\Testing\{RefreshDatabase, WithFaker};
use App\Actions\GenerateFolderDocuments;
use App\Events\FolderDocumentsGenerated;
use App\Models\{Folder, Set, Template};
use Illuminate\Support\Facades\Event;

uses(RefreshDatabase::class, WithFaker::class);

$contract_code = fake()->uuid();
$set_code = fake()->word();

afterAll(function () {
    app(Template::class)->clearMediaCollection(Template::COLLECTION_NAME);
    app(Folder::class)->clearMediaCollection(Folder::COLLECTION_NAME);
});

dataset('data', function () {
   return [
       [fn() => [
           "tct_no" => "002-9993-4",
           "interest" => "6",
           "witness1" => "",
           "witness2" => "",
           "buyer_tin" => "2238495759302",
           "buyer_name" => "MS. CELINA ERICA BELTRAN",
           "spouse_name" => "",
           "project_name" => "PASINAYA HOMES MAGALANG PAMPANGA",
           "tcp_in_words" => "SEVEN HUNDRED FIFTY THOUSAND",
           "buyer_address" => "UNIT 2509 SUNSHINE 100 MANDALUYONG CITY",
           "monthly_amort1" => "12500",
           "project_address" => "BRGY. DOLORES, MAGALANG, PAMPANGA",
           "co_borrower_name" => "",
           "repricing_period" => "5",
           "buyer_nationality" => "FILIPINO",
           "interest_in_words" => "SIX",
           "co_borrower_spouse" => "",
           "loan_period_months" => "60",
           "loan_term_in_years" => "5",
           "loan_terms_in_word" => "FIVE",
           "co_borrower_address" => "",
           "total_contract_price" => "750000",
           "buyer_civil_status_to" => "SINGLE",
           "technical_description" => "A PARCEL OF LAND (Lot 20 Blk 54 of consolidation sudi!ision lan (LRC) Pcs#$%2&5' in a o*tion of t+ consolidation of Lots 4,5$#A and 4,5$#B (LRC) Psd#505%%'Lot %' Psd#$00,0%' Lot $' Psd#$50-.0' LRC Rc/ Nos/ Nos/ N#2,024' 5$,&.' .-&%2' N#$$,.2' N#$%4&&' and 2$0,$ situatd in t+ Bo/ of an Donisio' 1un of Pa*anau' P*o! of Ri3al' s/ of Lu3on/ Boundd on NE/' oint 4 to $  Road Lot 22'",
           "co_borrower_nationality" => "NULL",
           "monthly_amort1_in_words" => "TWELVE THOUSAND FIVE HUNDRED.",
           "registry_of_deeds_address" => "BRGY. DOLORES, MAGALANG, PAMPANGA",
           "loan_value_after_downpayment_price" => "675000",
           "loan_value_after_downpayment_in_words" => "SIX HUNDRED SEVENTY-FIVE THOUSAND."
       ]]
   ];
});

dataset('set', function () use ($set_code) {
    return [
        [fn() => tap(Set::factory()->create(['code' => $set_code]), function (Set $set) {
            $template = Template::create([
                'code' => 'RLI-DOS-S',
                'name' => 'RLI Dead of Conditional Sale - Single',
                'url' => 'https://raw.githubusercontent.com/jn-devops/homeful-docs/main/DEED%20OF%20CONDITIONAL%20SALE%20END%20USER.docx',
            ]);
            $set->templates()->attach($template);
            $set->save();
        })]
    ];
});

test('generate folder documents action works', function (Set $set, array $data) use ($contract_code, $set_code) {
    Event::fake(FolderDocumentsGenerated::class);
    $action = app(GenerateFolderDocuments::class);
    $folder_data = $action->run($set, [
        'code' => $contract_code,
        'data' => $data
    ]);
    if ($folder_data instanceof \App\Data\FolderData) {
        expect($folder_data->code)->toBe($contract_code);
        expect($folder_data->set_code)->toBe($set->code);
        expect($folder_data->data)->toBe($data);
        expect($folder_data->generatedFiles)->toHaveCount(1);
//        $folder->documents->each(function (Spatie\MediaLibrary\MediaCollections\Models\Media $document) {
//            $document->delete();
//        });
    }
//    Event::assertDispatched(FolderDocumentsGenerated::class, function (FolderDocumentsGenerated $event) use($folder) {
//        return $event->folder->is($folder);
//    });
//    $set->templates->each(function (Template $template) {
//        $template->document->delete();
//    });
})->with('set', 'data' );

test('generate folder documents the second time', function (Set $set, array $data) use ($contract_code, $set_code) {
    $action = app(GenerateFolderDocuments::class);
    $folder1 = $action->run($set, [
        'code' => $contract_code,
        'data' => $data
    ]);
    expect($folder1->generatedFiles)->toHaveCount(1);
    $folder2 = $action->run($set, [
        'code' => $contract_code,
        'data' => $data
    ]);
    expect($folder2->generatedFiles)->toHaveCount(1);
//    expect($folder1->is($folder2))->toBeTrue();
//    expect($folder1->toArray())->toBe($folder2->toArray());
//    dd($folder1->generatedFiles[0]->url, $folder2->generatedFiles[0]->url);
})->with('set', 'data' );

test('generate folder documents end points works', function (Set $set, array $data) use ($contract_code, $set_code) {
    Event::fake(FolderDocumentsGenerated::class);
    $payload = ['code' => $contract_code, 'data' => $data];

    $response = $this->post(route('folder-documents', ['set' => $set->code]), $payload);
    expect($response->status())->toBe(201);
//    expect($response->json('data.code'))->toBe($contract_code);
    expect($response->json('code'))->toBe($contract_code);
    $folder = app(Folder::class)->where('code', $contract_code)->first();
    if ($folder instanceof Folder) {
        expect($folder->set_code)->toBe($set->code);
        expect(array_filter($folder->data))->toBe(array_filter($data));
        expect($folder->documents)->toHaveCount(1);
//        $folder->documents->each(function (Spatie\MediaLibrary\MediaCollections\Models\Media $document) {
////            dd($document->getUrl());
//
//            $document->delete();
//        });
    }
    Event::assertDispatched(FolderDocumentsGenerated::class, function (FolderDocumentsGenerated $event) use($folder) {
        return $event->folder->is($folder);
    });
    $set->templates->each(function (Template $template) {
        $template->document->delete();
    });
})->with('set', 'data' );
