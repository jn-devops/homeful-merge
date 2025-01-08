<?php

namespace App\Livewire;

use App\Models\ClientInformations;
use App\Models\Documents;
use ConvertApi\ConvertApi;
use Livewire\Component;
use Illuminate\Database\Eloquent\Model;
use PhpOffice\PhpWord\IOFactory;
use Dompdf\Dompdf;
use Dompdf\Options;
use PhpOffice\PhpWord\TemplateProcessor;

use PhpOffice\PhpWord\Settings;
use setasign\Fpdi\TcpdfFpdi;
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

        }

        return view('livewire.document-preview-component');
    }


    public function streamPdf()
    {

        if ($this->record){
            $mailmerge = new \Homeful\Mailmerge\Mailmerge();
            try{
                $converted_path =$mailmerge->generateDocument(storage_path('app/public/test1.docx'), ['buyer_name' => 'sample name'], 'test', 'public', false);
            }catch (\Exception $e){
                dd($e);
            }

            return response()->file($converted_path, [
                'Content-Type' => 'application/pdf',
                'Content-Disposition' => 'inline; filename="' . basename($converted_path) . '"'
            ]);

        }
    }
}
