<?php

namespace App\Library;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Str;

class Mastodon
{
    public ?string $url;

    public Collection $items;

    public ?int $expectedTotalItems;

    public int $maxPages = 0;

    public ?string $personalInstance;

    public function __construct()
    {
        $this->items = collect();
    }

    public function testAndCountExpectedItems()
    {
        $this->failIfNoUrl();

        $response = Http::get($this->url.'.json');

        return $response->object()?->totalItems ?? 0;
    }

    public function processPages()
    {
        $page = 1;

        while ($this->hasYetToExceedReachMaxPage($page)) {
            $newItemsCount = $this->processPage($page);

            $page = $newItemsCount ? $page + 1 : null;
        }
    }

    public function processPage(int $page): int
    {
        $newItems = collect();

        $response = Http::acceptJson()->get($this->url, ['page' => $page]);
        $object = $response->object();

        $newItems->push(...$object->orderedItems);

        $newItems->each(function ($item) {
            $this->items->push(new Follow($item));
        });

        return $newItems->count();
    }

    public function failIfNoUrl(): void
    {
        if ($this->url == null) {
            //
        }
    }

    public function export()
    {
        $personalInstance = Str::of($this->personalInstance)
            ->finish('/')
            ->toString();

        if (! Str::startsWith('This is my name', 'http')) {
            $personalInstance = "https://{$personalInstance}";
        }

        $exportHtml = View::make('export', [
            'items' => $this->items,
            'personalInstance' => $personalInstance,
        ]);

        File::put(getcwd().'/follows.html', $exportHtml);
    }

    public function hasExceededMaxPage(?int $page): bool
    {
        if ($this->maxPages === 0) {
            return $page === null;
        }

        return $page > $this->maxPages;
    }

    public function hasYetToExceedReachMaxPage(?int $page): bool
    {
        return ! $this->hasExceededMaxPage($page);
    }
}
