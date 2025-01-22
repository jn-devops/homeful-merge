<?php

namespace App\Livewire;


use Livewire\Component;
use Illuminate\Database\Eloquent\Model;
use PhpOffice\PhpWord\IOFactory;

use PhpOffice\PhpWord\TemplateProcessor;

use PhpOffice\PhpWord\Settings;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Process\Process;
use Illuminate\Support\Facades\File;


class DocumentPreviewComponent extends Component
{

    public ?Model $record = null;
    public ?string $html_code = null;


    public function mount($record)
    {
        $this->record = $record;
    }

    public function render()
    {
        if ($this->record) {
            if (!File::exists(storage_path('app/public/converted_documents/'))) {
                File::makeDirectory(storage_path('app/public/converted_documents/'), 0755, true);
            }
            if (!File::exists(storage_path('app/public/converted_pdf/'))) {
                File::makeDirectory(storage_path('app/public/converted_pdf/'), 0755, true);
            }
//            $mailmerge = new \Homeful\Mailmerge\Mailmerge();
//
////            dd($this->record->document->getPath(),storage_path('app/public/test1.docx'));
////            dd(pathinfo($this->record->document->getPath(), PATHINFO_FILENAME));
//            try{
//                $converted_path =$mailmerge->generateDocument($this->record->document->getPath(),
//                    json_decode($this->record->data, true)??[],
//                    pathinfo($this->record->document->getPath(), PATHINFO_FILENAME),
//                    'public',
//                    false);
//
////                dd($converted_path);
//            }catch (\Exception $e){
//                dd($e, $e->getMessage());
//            }

        }

        return view('livewire.document-preview-component');
    }


    public function streamPdf()
    {


        if ($this->record){
            $mailmerge = new \Homeful\Mailmerge\Mailmerge();

//            dd($this->record->document->getPath(),storage_path('app/public/test1.docx'));
//            dd(pathinfo($this->record->document->getPath(), PATHINFO_FILENAME));
//            dd(json_decode($this->record->data,true));
//            dd(json_decode($this->record->data,true));
            try{
                $converted_path =$mailmerge->generateDocument($this->record->document->getPath(),
                    json_decode($this->record->data,true),
                    pathinfo($this->record->document->getPath(), PATHINFO_FILENAME),
                    'public',
                    false);
            }catch (\Exception $e){
                dd($e, $e->getMessage());
            }

            return response()->file($converted_path, [
                'Content-Type' => 'application/pdf',
                'Content-Disposition' => 'inline; filename="' . basename($converted_path) . '"'
            ]);

        }
    }
}
