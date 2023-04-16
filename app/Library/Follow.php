<?php

namespace App\Library;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class Follow
{
    public ?object $raw;

    public bool $hasData = false;

    public function __construct(public string $url)
    {
    }

    public function gatherData()
    {
        try {
            $response = Http::acceptJson()->get($this->url);

            $this->raw = $response->object();
            $this->hasData = true;
        } catch (\Exception $e) {
        }
    }

    public function getName()
    {
        return $this->raw?->name ?? '';
    }

    public function getId()
    {
        return $this->raw?->id ?? '';
    }

    public function getSummary()
    {
        return $this->raw?->summary ?? '';
    }

    public function getType()
    {
        return $this->raw?->type ?? '';
    }

    public function getPreferredUsername()
    {
        return $this->raw?->preferredUsername ?? '';
    }

    public function getUrl()
    {
        return $this->raw?->url ?? '';
    }

    public function getManuallyApprovesFollowers()
    {
        return $this->raw?->manuallyApprovesFollowers ?? '';
    }

    public function getIcon()
    {
        return $this->raw?->icon?->url ?? '';
    }

    public function getImage()
    {
        return $this->raw?->image?->url ?? '';
    }

    public function tryToCalculateHandle()
    {
        $url = $this->getUrl() ?? $this->url;

        $username = Str::of($url)->afterLast('@');
        $domain = Str::of($url)
            ->after('https://')
            ->before('/');

        return "@{$username}@{$domain}";
    }
}
