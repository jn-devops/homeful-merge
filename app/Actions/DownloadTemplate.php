<?php

namespace App\Actions;

use Lorisleiva\Actions\Concerns\AsAction;
use Lorisleiva\Actions\ActionRequest;
use App\Events\TemplateDownloaded;
use App\Models\Template;

class DownloadTemplate
{
    use AsAction;

    protected function download(Template $template, string $url): Template
    {
        $template->document = $url;
        $template->refresh();
        TemplateDownloaded::dispatch($template);

        return $template;
    }

    public function handle(Template $template, string $url): Template|bool
    {
        return filter_var($url, FILTER_VALIDATE_URL)
            ? $this->download($template, $url)
            : false;
    }

    public function rules(): array
    {
        return [
            'url' => ['required', 'url']
        ];
    }

    public function asController(Template $template, ActionRequest $request): \Illuminate\Http\RedirectResponse
    {
        $template = $this->download($template, $request->validated(['url']));

        return back()->with(['code' => $template->code]);
    }
}
