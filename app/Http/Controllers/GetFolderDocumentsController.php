<?php

namespace App\Http\Controllers;

use App\Actions\GenerateFolderDocuments;
use App\Http\Resources\FolderResource;
use Illuminate\Http\Request;
use App\Models\Set;

class GetFolderDocumentsController extends Controller
{
    public function __construct(public GenerateFolderDocuments $action){}

    public function __invoke(Set $set, Request $request)
    {
        $folder = $this->action->run($set, $request->all());

        return $folder;
//        return new FolderResource($folder);
    }
}
