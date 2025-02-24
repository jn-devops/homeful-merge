<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use App\Actions\DownloadTemplate;
use Inertia\Inertia;

//Route::get('/', function () {
//    return Inertia::render('Welcome', [
//        'canLogin' => Route::has('login'),
//        'canRegister' => Route::has('register'),
//        'laravelVersion' => Application::VERSION,
//        'phpVersion' => PHP_VERSION,
//    ]);
//});

//Route::get('/dashboard', function () {
//    return Inertia::render('Dashboard');
//})->middleware(['auth', 'verified'])->name('dashboard');
//
Route::middleware('auth')->group(function () {
//    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
//    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
//    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::post('/download-template/{template}', DownloadTemplate::class)->name('download-template');
});

Route::get('/document-stream/{id}', function ($id) {
    $record = \App\Models\Template::findOrFail($id);
    $component = new \App\Livewire\DocumentPreviewComponent();
    $component->record = $record;
    return $component->streamPdf();
})->middleware(['auth'])->name('document.stream');

require __DIR__.'/auth.php';
