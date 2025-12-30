<?php

namespace lib\html;

class TmhHtmlListEntity
{
    public function transform(array $entity): void
    {
        foreach ($entity['entity_lists'] as $entityList) {
            echo $entityList['translation'] . PHP_EOL;
            foreach ($entityList['items'] as $entityListItem) {
                $title = $entityListItem['title'];
                $href = $entityListItem['href'];
                $innerHtml = $entityListItem['innerHtml'];
                echo '<a title="' . $title . '" href="' . $href . '"/>' . $innerHtml . '</a>' . PHP_EOL;
            }
        }
    }
}
