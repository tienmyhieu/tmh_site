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
    }
}
