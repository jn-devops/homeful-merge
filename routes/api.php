<?php

use App\Http\Controllers\GetFolderDocumentsController;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('folder-documents/{set}', GetFolderDocumentsController::class)->name('folder-documents');
