<?php

use App\Http\Controllers\DocumentController;
use App\Http\Controllers\GetFolderDocumentsController;
use App\Http\Controllers\SetController;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('folder-documents/{set}', GetFolderDocumentsController::class)->name('folder-documents');
Route::post('sets',SetController::class)->name('sets');
Route::post('templates/{set_code}', [DocumentController::class, 'templates'])->name('templates');
Route::post('generate-document/{template_code}', [DocumentController::class, 'generateDocument'])->name('generate.document');
