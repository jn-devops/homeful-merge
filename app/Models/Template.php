<?php

namespace App\Models;

use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Spatie\MediaLibrary\InteractsWithMedia;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use App\Traits\HasDocument;

/**
 * Class Template
 *
 * @property string $id
 * @property string $code
 * @property string $name
 * @property string $url
 * @property string $data
 * @property ?Media $document
 *
 * @method int getKey()
 *
 */
class Template extends Model implements HasMedia
{
    /** @use HasFactory<\Database\Factories\TemplateFactory> */
    use InteractsWithMedia;
    use HasDocument;
    use HasFactory;
    use HasUuids;

    protected $fillable = [
        'code',
        'name',
        'url',
        'data'
    ];

    /**
     * This is the pointer to the collection.
     *
     * @var string
     */
    const COLLECTION_NAME = 'template-documents';

    /**
     * This is applicable to .pdf and .docx only.
     *
     * @var array
     */
    const DOCUMENT_MIME_TYPES = [
        'application/pdf',
        'application/vnd.openxmlformats-officedocument.wordprocessingml.document'
    ];

    public function fields(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(Field::class);
    }

    public function registerMediaCollections(): void
    {
        $this->addDocumentsCollection();
    }
}
