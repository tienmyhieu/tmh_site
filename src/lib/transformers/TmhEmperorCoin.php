<?php

namespace lib\transformers;

use lib\core\TmhLocale;

readonly class TmhEmperorCoin
{
    private array $parentRoute;

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
        $this->setParentRoute($entity['route'], $entity['type']);
        $transformed['image_group_lists'] = [];
        foreach ($entity['image_group_lists'] as $imageGroupList) {
            $transformedImageGroupList = [];
            $transformedImageGroupList['translation'] = $this->locale->get($imageGroupList['translation']);
            $transformedImageGroupList['items'] = [];
            $imageGroups = $this->filterActiveImageGroups($imageGroupList['items']);
            foreach ($imageGroups as $imageGroup) {
                $imageGroup['route'] = $this->imageGroupRoute($imageGroup);
                $imageGroup['translation'] = $this->imageGroupTranslation($imageGroup);
                $transformedImageGroupList['items'][] = $this->imageGroup->transform($imageGroup, $entity['type']);
            }
            $transformed['image_group_lists'][] = $transformedImageGroupList;
        }

        return $transformed;
    }

    private function imageGroupRoute(array $imageGroup): array
    {
        $transformed = $this->parentRoute;
        $transformed['href'] .= '/'. strtoupper($imageGroup['code']);
        return $transformed;
    }

    private function imageGroupTranslation(array $imageGroup): string
    {
        return strtoupper(substr($imageGroup['code'], 0, 4)) . ' ' . $imageGroup['identifier'];
    }

    private function setParentRoute(string $route, string $type): void
    {
        $entity = ['entity' => $route, 'type' => $type];
        $this->parentRoute = $this->routeTransformer->transform($entity, $type);
    }

    private function filterActiveImageGroups(array $imageGroups): array
    {
        return array_filter($imageGroups, function($imageGroup) {
            return $imageGroup['active'] == '1';
        });
    }
}
