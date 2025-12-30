<?php

namespace lib\html;

class TmhHtmlMetalCoin
{
    public function transform(array $entity): void
    {
        foreach ($entity['image_group_lists'] as $imageGroupList) {
            if (0 < strlen($imageGroupList['translation'])) {
                echo $imageGroupList['translation'] . PHP_EOL;
            }
            foreach ($imageGroupList['items'] as $imageGroup) {
                $imgHref = $imageGroup['route']['href'];
                $imgTitle = $imageGroup['route']['title'];
                foreach ($imageGroup['images'] as $image) {
                    echo '<a title="' . $imgTitle . '" href="' . $imgHref . '"/>';
                    echo '<img alt="' . $image['alt'] . '" src="' . $image['src'] . '"/></a>';
                }
                echo PHP_EOL;
            }
        }
    }
}
