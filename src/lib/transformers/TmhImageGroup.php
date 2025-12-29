<?php

namespace lib\transformers;

use lib\core\TmhDatabase;
use lib\core\TmhLocale;

readonly class TmhImageGroup
{
    public function __construct(private TmhDatabase $database, private TmhLocale $locale)
    {
    }

    public function transform(array $entity, string $type): array
    {
        $baseSrc = 'http://img1.tienmyhieu.com/images/128/';
        $imageGroup = $this->database->imageGroup($entity['image_group']);
        $transformed = ['date' => $imageGroup['date'], 'images' => []];
        foreach ($imageGroup['images'] as $image) {
            $img = $this->database->image($image);
            $imgAlt = implode(' ', $this->locale->getMany($img['alt']));
            $imgSrc = $baseSrc . $img['src'] . '.jpg';
            $transformed['images'][] = ['alt' => $imgAlt, 'src' => $imgSrc];
        }
        return $transformed;
    }
}
