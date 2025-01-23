<?php

namespace App\Models;

use Spatie\MediaLibrary\MediaCollections\MediaCollection;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Spatie\MediaLibrary\InteractsWithMedia;
use Illuminate\Database\Eloquent\Model;
use App\Traits\HasMultipleDocuments;
use Spatie\MediaLibrary\HasMedia;

/**
 * Class Folder
 *
 * @property string $id
 * @property string $code
 * @property array $meta
 * @property \Spatie\MediaLibrary\MediaCollections\Models\Collections\MediaCollection $documents
 *
 * @method int getKey()
 * @method Media addDocument(string|\Symfony\Component\HttpFoundation\File\UploadedFile $file)
 * @method \Spatie\MediaLibrary\MediaCollections\Models\Collections\MediaCollection getDocuments()
 *
 */
class Folder extends Model implements HasMedia
{
    /** @use HasFactory<\Database\Factories\FolderFactory> */
    use HasMultipleDocuments;
    use InteractsWithMedia;
    use HasFactory;
    use HasUuids;

    protected $fillable = [
        'code',
        'meta',
    ];

    protected $casts = [
        'meta' => 'array'
    ];

    protected $appends = [
        'documents'
    ];
    /**
     * This is the pointer to the collection.
     *
     * @var string
     */
    const COLLECTION_NAME = 'folder-documents';

    /**
     * This is applicable to .pdf only.
     *
     * @var array
     */
    const DOCUMENT_MIME_TYPES = [
        'application/pdf',
    ];

    public function getDocumentsAttribute(): \Spatie\MediaLibrary\MediaCollections\Models\Collections\MediaCollection
    {
        return $this->getDocuments();
    }
}
