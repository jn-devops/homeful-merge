<?php

use Illuminate\Foundation\Testing\{RefreshDatabase, WithFaker};
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use App\Models\{Folder, Set, Template};

uses(RefreshDatabase::class, WithFaker::class);

it('has mailmerge', function () {

    $data = [
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
    ];
    $json = json_encode($data);
    $template = Template::create([
        'code' => 'RLI-DOS-S',
        'name' => 'RLI Dead of Conditional Sale - Single',
        'url' => 'https://raw.githubusercontent.com/jn-devops/homeful-docs/main/DEED%20OF%20CONDITIONAL%20SALE%20END%20USER.docx',
//        'data' => $json
    ]);

    $template->document = $template->url;
    expect($template)->toBeInstanceOf(Template::class);
    expect($template->document)->toBeInstanceOf(Media::class);
    $template_path = $template->document->getPath();
    expect(file_exists($template_path))->toBeTrue();
    $template_filename = pathinfo($template_path, PATHINFO_FILENAME);

    $folder = Folder::factory()->create();
    expect($folder->documents)->toHaveCount(0);

    $mailmerge = new \Homeful\Mailmerge\Mailmerge();
    $converted_path1 = $mailmerge->generateDocument(filePath: $template_path, arrInput: $data, filename: $template_filename . '1');
    $converted_path2 = $mailmerge->generateDocument(filePath: $template_path, arrInput: $data, filename: $template_filename . '2', disk: 'public', download: false);

    expect(file_exists($converted_path1))->toBeTrue();
    expect(file_exists($converted_path2))->toBeTrue();

    $folder->addDocument($converted_path1);
    $folder->addDocument($converted_path2);
    $folder->refresh();

    expect($folder->documents)->toHaveCount(2);

    $template->clearMediaCollection(Template::COLLECTION_NAME);
    $folder->clearMediaCollection(Folder::COLLECTION_NAME);
});
