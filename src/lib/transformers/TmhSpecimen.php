<?php

namespace lib\transformers;

use lib\core\TmhLocale;

readonly class TmhSpecimen
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
        $transformed['type'] = $entity['type'];
        $transformedImageGroupList = [
            'translation' => $this->locale->get($entity['image_group_list']['translation']),
            'items'  => []
        ];
        foreach ($entity['image_group_list']['items'] as $imageGroup) {
            $routeEntity = ['entity' => $imageGroup['source'], 'type' => $entity['type']];
            $sourceRoute = $this->routeTransformer->transform($routeEntity, $entity['type']);
            $transformedImageGroup = $this->imageGroup->transform($imageGroup, $entity['type']);
            if ($entity['dated'] == '1') {
                $identifier = strtoupper($sourceRoute['code']) . ' ' . $imageGroup['identifier'];
                $identifierPrefix = $transformedImageGroup['date'] . ' - ';
                $transformedImageGroup['translation'] = $identifierPrefix . $identifier;
            } else {
                $transformedImageGroup['translation'] = '';
            }
            $transformedImageGroupList['items'][] = $transformedImageGroup;
        }
        $transformed['image_group_list'] = $transformedImageGroupList;
        return $transformed;
    }
}
