<?php

namespace lib\html;

class TmhHtmlSpecimen
{
    public function transform(array $entity): void
    {
        $imgTitle = $entity['obverse'] . ' ' . $entity['reverse'];
        foreach ($entity['image_group_list']['items'] as $imageGroup) {
            if (0 < strlen($imageGroup['translation'])) {
                echo $imageGroup['translation'] . PHP_EOL;
            }
            foreach ($imageGroup['images'] as $image) {
                $imgHref = str_replace('/128/', '/1024/', $image['src']);
                echo '<a title="' . $imgTitle . '" href="' . $imgHref . '"/>';
                echo '<img alt="' . $image['alt'] . '" src="' . $image['src'] . '"/></a>';
            }
            echo PHP_EOL;
        }
        $this->keyValues($entity['key_values']);
    }

    private function keyValues(array $keyValues): void
    {
        foreach ($keyValues as $keyValue) {
            echo $keyValue['key'] . ': ';
            match($keyValue['value']['type']) {
                'route' => $this->routeKeyValue($keyValue['value']),
                default => $this->textKeyValue($keyValue['value'])
            };
            echo PHP_EOL;
        }
    }

    private function routeKeyValue(array $value): void
    {
        $title = $value['value']['title'];
        $href = $value['value']['href'];
        $innerHtml = $value['value']['innerHtml'];
        echo '<a title="' . $title . '" href="' . $href . '"/>' . $innerHtml . '</a>';
    }

    private function textKeyValue(array $value): void
    {
        $hasLanguage = 0 < strlen($value['lang']);
        $lang = $hasLanguage ? ' lang="' . $value['lang'] . '"' : '';
        echo '<span' . $lang .'>' . $value['value'] . '</span>';
    }
}
