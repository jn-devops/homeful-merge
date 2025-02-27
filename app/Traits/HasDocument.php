<?php

namespace App\Traits;

use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Spatie\MediaLibrary\MediaCollections\File;
use Illuminate\Support\Facades\Storage;

trait HasDocument
{
    /**
     * Documents should always point to a single file.
     */
    protected function addDocumentsCollection(): void
    {
        $this->addMediaCollection(self::COLLECTION_NAME)
            ->singleFile()
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
            ->usingName('document')
            ->toMediaCollection(self::COLLECTION_NAME);
    }

    /**
     * @param string|null $url
     * @return HasDocument|\App\Models\Template
     * @throws \Spatie\MediaLibrary\MediaCollections\Exceptions\FileCannotBeAdded
     * @throws \Spatie\MediaLibrary\MediaCollections\Exceptions\FileDoesNotExist
     * @throws \Spatie\MediaLibrary\MediaCollections\Exceptions\FileIsTooBig
     */
    public function setDocumentAttribute(?string $url): self
    {
        if ($url) {
            $this->addMediaFromUrl($url)
                ->usingName('document')
                ->toMediaCollection(self::COLLECTION_NAME);
        }

        return $this;
    }

    /**
     * @return Media|null
     */
    public function getDocumentAttribute(): ?Media
    {
//        return $this->getFirstMedia(self::COLLECTION_NAME);
        return $this->getMedia(self::COLLECTION_NAME)->last();
    }
}
