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
        $coin = $this->locale->get($entity['topic']);
        $transformed['title'] = $this->locale->get($entity['title']);
        $transformed['topic'] = $coin;
        $transformed['type'] = $entity['type'];
        $transformed['image_group_lists'] = [];
        foreach ($entity['image_group_lists'] as $imageGroupList) {
            $transformedImageGroupList = [];
            $transformedImageGroupList['translation'] = $this->locale->get($imageGroupList['translation']);
            $transformedImageGroupList['items'] = [];
            $emperor = $this->locale->get($imageGroupList['translation']);
            foreach ($imageGroupList['items'] as $imageGroup) {
                $transformedImageGroup = $this->imageGroup->transform($imageGroup, $entity['type']);;
                $routeEntity = ['entity' => $imageGroup['route'], 'type' => $entity['type']];
                $transformedImageGroup['route'] = $this->routeTransformer->transform($routeEntity, $entity['type']);
                $transformedImageGroup['translation'] = $emperor . ' ' . $coin;
                $transformedImageGroupList['items'][] = $transformedImageGroup;
            }
            $transformed['image_group_lists'][] = $transformedImageGroupList;
        }
        return $transformed;
    }
}
