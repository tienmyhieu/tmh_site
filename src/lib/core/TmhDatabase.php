<?php

namespace lib\core;

class TmhDatabase
{
    private array $images = [];
    private array $imageGroups = [];

    public function __construct(private readonly TmhJson $json)
    {
    }

    public function image(string $uuid): array
    {
        $images = $this->getImages();
        return in_array($uuid, array_keys($images)) ? $images[$uuid] : [];
    }

    public function imageGroup(string $uuid): array
    {
        $imageGroups = $this->getImageGroups();
        return in_array($uuid, array_keys($imageGroups)) ? $imageGroups[$uuid] : [];
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
}