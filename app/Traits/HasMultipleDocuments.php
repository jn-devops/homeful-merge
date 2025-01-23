<?php

namespace App\Traits;

use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Spatie\MediaLibrary\MediaCollections\File;
use Illuminate\Support\Facades\Storage;

trait HasMultipleDocuments
{
    /**
     * Add to collection.
     */
    protected function addDocumentsCollection(): void
    {
        $this->addMediaCollection(self::COLLECTION_NAME)
            ->acceptsFile(function(File $file) {
                return in_array(needle: $file->mimeType, haystack: self::DOCUMENT_MIME_TYPES);
            });
    }

    /**
     * @param string|\Symfony\Component\HttpFoundation\File\UploadedFile $file
     * @return Media
     * @throws \Spatie\MediaLibrary\MediaCollections\Exceptions\FileDoesNotExist
     * @throws \Spatie\MediaLibrary\MediaCollections\Exceptions\FileIsTooBig
     */
    public function addDocument(string|\Symfony\Component\HttpFoundation\File\UploadedFile $file): Media
    {
        $file = file_exists($file) ? $file : Storage::path($file);

        return $this->addMedia(file: $file)
            ->sanitizingFileName(function($fileName) {
                return strtolower(str_replace(['#', '/', '\\', ' '], '-', $fileName));
            })
            ->toMediaCollection(self::COLLECTION_NAME);
    }

    /**
     * @return \Spatie\MediaLibrary\MediaCollections\Models\Collections\MediaCollection
     */
    public function getDocuments(): \Spatie\MediaLibrary\MediaCollections\Models\Collections\MediaCollection
    {
        return $this->getMedia(self::COLLECTION_NAME);
    }
}
