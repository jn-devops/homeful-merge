<?php

namespace App\Http\Controllers;

use App\Actions\GenerateFolderDocuments;
use App\Http\Resources\FolderResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Models\Set;
use Throwable;

class GetFolderDocumentsController extends Controller
{
    public function __construct(public GenerateFolderDocuments $action){}

    public function __invoke(Set $set, Request $request): JsonResponse
    {
        try {
            $folder = $this->action->run($set, $request->all());
            return response()->json([
                'success' => true,
                'message' => 'Folder documents generated successfully.',
                'data' => $folder['data'],
            ], 201);
        } catch (Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to generate folder documents.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
