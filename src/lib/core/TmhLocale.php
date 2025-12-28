<?php

namespace lib\core;

readonly class TmhLocale
{
    private string $locale;
    private array $locales;

    public function __construct(private TmhDomain $domain, private TmhJson $json)
    {
        $this->locale = $this->domain->getLocale();
        $this->locales = $this->json->locale($this->locale);
    }

    public function get(string $uuid): string
    {
        return in_array($uuid, array_keys($this->locales)) ? $this->locales[$uuid] : $uuid;
    }

    public function locale(): string
    {
        return $this->locale;
    }

    public function locales(): array
    {
        return $this->locales;
    }
}
