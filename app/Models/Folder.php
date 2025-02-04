<?php

namespace App\Models;

use Illuminate\Support\Str;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Spatie\MediaLibrary\InteractsWithMedia;
use Illuminate\Database\Eloquent\Model;
use App\Traits\HasMultipleDocuments;
use Homeful\Common\Traits\HasMeta;
use Spatie\MediaLibrary\HasMedia;

/**
 * Class Folder
 *
 * @property string $id
 * @property string $code
 * @property string $set_code
 * @property array $data
 * @property \Spatie\MediaLibrary\MediaCollections\Models\Collections\MediaCollection $documents
 * @property array $generatedFiles
 *
 * @method int getKey()
 * @method Media addDocument(string|\Symfony\Component\HttpFoundation\File\UploadedFile $file)
 * @method \Spatie\MediaLibrary\MediaCollections\Models\Collections\MediaCollection getDocuments()
 * @method int removeDocuments()
 */
class Folder extends Model implements HasMedia
{
    const SET_CODE_FIELD = 'set_code';
    const DATA_FIELD = 'data';

    /** @use HasFactory<\Database\Factories\FolderFactory> */
    use HasMultipleDocuments;
    use InteractsWithMedia;
    use HasFactory;
    use HasUuids;
    use HasMeta;

    protected $fillable = [
        'code',
        'set_code',
        'data'
    ];

    protected $casts = [

    ];

    protected $appends = [
        'documents', 'media'
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

    /**
     * @param string|null $code
     * @return $this
     */
    public function setSetCodeAttribute(?string $code): self
    {
        $this->getAttribute('meta')->set(Folder::SET_CODE_FIELD, $code);

        return $this;
    }

    /**
     * @return string|null
     */
    public function getSetCodeAttribute(): ?string
    {
        return $this->getAttribute('meta')->get(Folder::SET_CODE_FIELD);
    }

    /**
     * @param array|null $data
     * @return $this
     */
    public function setDataAttribute(?array $data): self
    {
        $this->getAttribute('meta')->set(Folder::DATA_FIELD, $data);

        return $this;
    }

    /**
     * @return array|null
     */
    public function getDataAttribute(): ?array
    {
        return $this->getAttribute('meta')->get(Folder::DATA_FIELD);
    }

    /**
     * Attribute Rationale - so it can be serialized and included in the appends.
     *
     * @return \Spatie\MediaLibrary\MediaCollections\Models\Collections\MediaCollection
     */
    public function getDocumentsAttribute(): \Spatie\MediaLibrary\MediaCollections\Models\Collections\MediaCollection
    {
        return $this->getDocuments();
    }

    public function getGeneratedFilesAttribute(): array
    {
        return collect($this->media)
            ->mapWithKeys(function ($item, $key) {
                $collection_name = $item['collection_name'];
                $name = Str::camel(Str::singular($collection_name));
//                $url = $item['original_url'];
                $url = $item->getUrl();

                return [
                    $key => [
                        'name' => $name,
                        'url' => $url,
                    ],
                ];
            })
            ->toArray();
    }
}
