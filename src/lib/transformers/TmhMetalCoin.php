<?php

namespace lib\transformers;

use lib\core\TmhLocale;

readonly class TmhMetalCoin
{
    public function __construct(
        private TmhImageGroup $imageGroup,
        private TmhLocale $locale,
        private TmhRouteTransformer $routeTransformer
    ) {
    }

    public function transform(array $entity): array
    {
        $transformed = [];
        $transformed['obverse'] = $this->locale->get($entity['obverse']);
        $transformed['reverse'] = $this->locale->get($entity['reverse']);
        $transformed['image_group_lists'] = [];
        foreach ($entity['image_group_lists'] as $imageGroupList) {
            $transformedImageGroupList = [];
            $transformedImageGroupList['translation'] = $this->locale->get($imageGroupList['translation']);
            $transformedImageGroupList['items'] = [];
            foreach ($imageGroupList['items'] as $imageGroup) {
                $routeEntity = ['entity' => $imageGroup['source'], 'type' => $entity['type']];
                $imageGroup['route'] = $this->routeTransformer->transform($routeEntity, $entity['type']);
                $imageGroup['translation'] = $this->locale->get($imageGroupList['translation']);
                $transformedImageGroupList['items'][] = $this->imageGroup->transform($imageGroup, $entity['type']);
            }
            $transformed['image_group_lists'][] = $transformedImageGroupList;
        }
        return $transformed;
    }
}