<?php

use Illuminate\Foundation\Testing\{RefreshDatabase, WithFaker};
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use App\Actions\GenerateFolderDocuments;
use App\Models\{Folder, Set, Template};

uses(RefreshDatabase::class, WithFaker::class);

dataset('data', function () {
   return [
       [fn() => [
           "tct_no" => "002-9993-4",
           "interest" => "6",
           "witness1" => "",
           "witness2" => "",
           "buyer_tin" => "2238495759302",
           "buyer_name" => "CELINA ERICA BELTRAN",
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

$set_code = 'ABC';

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

test('generate folder documents action works', function (Set $set, array $data) use ($set_code) {
    $action = app(GenerateFolderDocuments::class);
    $folder = $action->run($set, [
        'code' => $set_code,
        'data' => $data
    ]);
    if ($folder instanceof Folder) {
        expect($folder->documents)->toHaveCount(1);
    }

})->with('set', 'data' );
