<?php

namespace lib\transformers;

use lib\core\TmhDatabase;
use lib\core\TmhLocale;

readonly class TmhDatabaseTransformer
{
    private const string CDN = 'http://img1.tienmyhieu.com/';

    public function __construct(private TmhDatabase $database, private TmhLocale $locale)
    {
    }

    public function imageGroup(string $uuid): array
    {
        $baseSrc = self::CDN . 'images/128/';
        $imageGroup = $this->database->imageGroup($uuid);
        $transformed = ['date' => $imageGroup['date'], 'images' => []];
        foreach ($imageGroup['images'] as $image) {
            $img = $this->database->image($image);
            $imgAlt = implode(' ', $this->locale->getMany($img['alt']));
            $imgSrc = $baseSrc . $img['src'] . '.jpg';
            $transformed['images'][] = ['alt' => $imgAlt, 'src' => $imgSrc];
        }
        return $transformed;
    }

    public function inscription(string $uuid): string
    {
        return $this->database->inscription($uuid);
    }

    public function uploadGroup(string $uuid): array
    {
        $baseSrc = self::CDN . 'uploads/128/';
        $uploadGroup = $this->database->uploadGroup($uuid);
        $transformed = ['type' => $uploadGroup['type'], 'uploads' => []];
        foreach ($uploadGroup['uploads'] as $uploadUuid) {
            $upload = $this->database->upload($uploadUuid);
            $uploadAlt = implode(' ', $this->locale->getMany($upload['alt']));
            $imgSrc = $baseSrc . $upload['src'] . '.jpg';
            $transformed['uploads'][] = ['alt' => $uploadAlt, 'src' => $imgSrc];
        }
        return $transformed;
    }
}
