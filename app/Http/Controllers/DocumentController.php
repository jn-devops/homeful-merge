<?php

namespace App\Http\Controllers;

use App\Models\Folder;
use App\Models\Set;
use App\Models\Template;
use Homeful\Mailmerge\Mailmerge;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class DocumentController extends Controller
{
    /**
     * @param Mailmerge $merge
     */
    public function __construct(public Mailmerge $merge){}

    public function removeFolderContents(string $code):void{
        $folder = Folder::firstOrCreate(['code' => $code]);
        $folder->removeDocuments();
    }

    public function templates(String $setCode): JsonResponse
    {
        try {
            $templates = Set::firstWhere('code', $setCode)?->templates->map(fn($template) => $template->code)->toArray() ?? [];

            return response()->json([
                'success' => true,
                'templates' => $templates
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while retrieving templates.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function generateDocument(string $template_code,Request $request)
    {
        try {

            $folder = Folder::firstOrCreate(['code' => $request->code]);
            // Fetch the template
            $template = Template::firstWhere('code', $template_code);

            // Check if template exists
            if (!$template) {
                return response()->json([
                    'success' => false,
                    'message' => 'Template not found.'
                ], 404);
            }

            // Ensure document exists
            if (!$template->document) {
                return response()->json([
                    'success' => false,
                    'message' => 'No document associated with this template.'
                ], 404);
            }


           $document= $folder->addDocument(file: $this->merge->generateDocument(
                filePath: $template->document->getPath(),
                arrInput: $request->data,
                filename: $template->title
            ));

            return response()->json([
                'success' => true,
                'name' => $document->name,
                'url' =>  $document->original_url,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while generating the document.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }




}
