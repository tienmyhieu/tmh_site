<?php

namespace lib\transformers;

use lib\core\TmhLocale;

readonly class TmhListEntity
{
    public function __construct(private TmhLocale $locale, private TmhRouteTransformer $routeTransformer)
    {
    }

    public function transform(array $entity): array
    {
        $transformed = [];
        $transformed['title'] = $this->locale->get($entity['title']);
        $transformed['topic'] = $this->locale->get($entity['topic']);
        $transformed['entity_lists'] = [];
        foreach ($entity['entity_lists'] as $entityList) {
            $transformedList = [];
            $transformedList['translation'] = $this->locale->get($entityList['translation']);
            $transformedList['items'] = [];
            $entityListItems = $this->filterActiveListItems($entityList['items']);
            foreach ($entityListItems as $entityListItem) {
                $transformedList['items'][] = $this->transformListItem($entityListItem, $entity['type']);
            }
            $transformed['entity_lists'][] = $transformedList;
        }
        return $transformed;
    }

    private function filterActiveListItems(array $listItems): array
    {
        return array_filter($listItems, function($listItem) {
            return $listItem['active'] == '1';
        });
    }

    private function transformListItem(array $listItem, string $type): array
    {
        return match($listItem['type']) {
            'route' => $this->routeTransformer->transform($listItem, $type),
            default => $this->transformText($listItem)
        };
    }

    private function transformText(array $entity): array
    {
        return [
            'innerHtml' => $this->locale->get($entity['translation']),
            'type' => $entity['type']
        ];
    }
}
