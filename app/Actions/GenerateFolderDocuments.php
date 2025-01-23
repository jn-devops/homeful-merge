<?php

namespace App\Actions;

use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Lorisleiva\Actions\Concerns\AsAction;
use Illuminate\Support\Facades\Validator;
use App\Events\FolderDocumentsGenerated;
use App\Models\{Folder, Set, Template};
use Homeful\Mailmerge\Mailmerge;
use Illuminate\Support\Arr;

class GenerateFolderDocuments
{
    use AsAction;

    /**
     * @param Mailmerge $merge
     */
    public function __construct(public Mailmerge $merge){}

    /**
     * @param Set $set
     * @param array $validated
     * @return Folder
     * @throws \Spatie\MediaLibrary\MediaCollections\Exceptions\FileDoesNotExist
     * @throws \Spatie\MediaLibrary\MediaCollections\Exceptions\FileIsTooBig
     */
    protected function generate(Set $set, array $validated): Folder
    {
        $folder = app(Folder::class)->create(array_merge($validated, ['set_code' => $set->code]));
        if ($folder instanceof Folder) {
            $data = Arr::get($validated, 'data');
            $set->templates->each(function (Template $template) use ($folder, $data) {
                $template->document = $template->url;//TODO: improve ternary
                if ($template->document instanceof Media) {
                    $folder->addDocument(file: $this->merge->generateDocument(
                        filePath: $template->document->getPath(),
                        arrInput: $data,
                        filename: $template->name
                    ));
                }
            });
            $folder->refresh();

            FolderDocumentsGenerated::dispatchif($folder->documents->count() > 0, $folder);
        }

        return $folder;
    }

    /**
     * @param Set $set
     * @param array $attribs
     * @return Folder
     * @throws \Spatie\MediaLibrary\MediaCollections\Exceptions\FileDoesNotExist
     * @throws \Spatie\MediaLibrary\MediaCollections\Exceptions\FileIsTooBig
     */
    public function handle(Set $set, array $attribs): Folder
    {
        $validated = Validator::validate($attribs, $this->rules());

        return $this->generate($set, $validated);
    }

    /**
     * @return array[]
     */
    public function rules(): array
    {
        return [
            'code' => ['required', 'string'],//contract code (if reference code, better)
            'data' => ['required', 'array'],//contract data
            'meta' => ['nullable', 'array'],
        ];
    }
}
