<?php

namespace App\Data;

use Spatie\LaravelData\DataCollection;
use Spatie\LaravelData\Optional;
use Spatie\LaravelData\Data;
use App\Models\Folder;

class FolderData extends Data
{
    public function __construct(
        public string $code,
        public string $set_code,
        public array $data,
        /** @var GeneratedFilesData[] */
        public DataCollection|Optional $generatedFiles,
    ) {}

    public static function fromModel(Folder $folder): static
    {
        return new FolderData(
            code: $folder->code,
            set_code: $folder->set_code,
            data: $folder->data,
            generatedFiles: new DataCollection(GeneratedFilesData::class, $folder->generatedFiles),
        );
    }
}

class GeneratedFilesData extends Data
{
    public function __construct(
        public string $name,
        public string $url
    ) {}
}
