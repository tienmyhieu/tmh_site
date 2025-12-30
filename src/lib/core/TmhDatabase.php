<?php

namespace lib\core;

class TmhDatabase
{
    private array $images = [];
    private array $imageGroups = [];
    private array $inscriptions = [];

    public function __construct(private readonly TmhJson $json)
    {
    }

    public function image(string $uuid): array
    {
        $images = $this->getImages();
        return in_array($uuid, array_keys($images)) ? $images[$uuid] : ['alt' => [], 'src' => ''];
    }

    public function imageGroup(string $uuid): array
    {
        $imageGroups = $this->getImageGroups();
        return in_array($uuid, array_keys($imageGroups)) ? $imageGroups[$uuid] : ['date' => '', 'images' => []];
    }

    public function inscription(string $uuid): string
    {
        $inscriptions = $this->getInscriptions();
        return in_array($uuid, array_keys($inscriptions)) ? $inscriptions[$uuid] : '';
    }

    private function getImages(): array
    {
        if (empty($this->images)) {
            $this->images = $this->json->database('image');
        }
        return $this->images;
    }

    private function getImageGroups(): array
    {
        if (empty($this->imageGroups)) {
            $this->imageGroups = $this->json->database('image_group');
        }
        return $this->imageGroups;
    }

    private function getInscriptions(): array
    {
        if (empty($this->inscriptions)) {
            $this->inscriptions = $this->json->database('inscription');
        }
        return $this->inscriptions;
    }
}