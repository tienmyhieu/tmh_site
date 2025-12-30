<?php

namespace lib\transformers;

use lib\core\TmhLocale;

class TmhSpecimen
{
    private array $sources;

    public function __construct(
        private TmhDatabaseTransformer $databaseTransformer,
        private TmhLocale $locale,
        private TmhRouteTransformer $routeTransformer
    ) {
        $this->initializeSources();
    }

    public function transform(array $entity): array
    {
        $transformed = [];
        $this->addSource($entity['source'], $entity['identifier'], $entity['type']);
        $transformed['key_values'] = [
            $this->getObverseKeyValue($entity['obverse']),
            $this->getReverseKeyValue($entity['reverse'])
        ];
        if (0 < strlen($entity['diameter'])) {
            $transformed['key_values'][] = $this->getDiameterKeyValue($entity['diameter']);
        }
        if (0 < strlen($entity['weight'])) {
            $transformed['key_values'][] = $this->getWeightKeyValue($entity['weight']);
        }
        if (0 < strlen($entity['variant'])) {
            $transformed['key_values'][] = $this->getVariantKeyValue($entity['variant']);
        }
        $transformed['obverse'] = $this->locale->get($entity['obverse']);
        $transformed['reverse'] = $this->locale->get($entity['reverse']);
        $transformed['type'] = $entity['type'];
        $transformedImageGroupList = [
            'translation' => $this->locale->get($entity['image_group_list']['translation']),
            'items' => []
        ];
        foreach ($entity['image_group_list']['items'] as $imageGroup) {
            $this->addSource($imageGroup['source'], $imageGroup['identifier'], $entity['type']);
            $transformedImageGroup = $this->databaseTransformer->imageGroup($imageGroup['image_group']);
            if ($entity['dated'] == '1') {
                $source = $this->sources[$imageGroup['source']];
                $identifierPrefix = $transformedImageGroup['date'] . ' - ';
                $transformedImageGroup['translation'] = $identifierPrefix . $source['identifier'];
            } else {
                $transformedImageGroup['translation'] = '';
            }
            $transformedImageGroupList['items'][] = $transformedImageGroup;
        }
        $transformed['image_group_list'] = $transformedImageGroupList;
        foreach ($this->sources as $source) {
            $transformed['key_values'][] = [
                'key' => $this->locale->get('48w95ukn'),
                'value' => ['lang' => '', 'value' => $source['route'], 'type' => 'route']
            ];
        }
        return $transformed;
    }

    private function addSource(string $source, string $identifier, string $type): void
    {
        if (!in_array($source, array_keys($this->sources))) {
            $routeEntity = ['entity' => $source, 'type' => $type];
            $sourceRoute = $this->routeTransformer->transform($routeEntity, $type);
            $identifier = strtoupper($sourceRoute['code']) . ' ' . $identifier;
            $sourceRoute['innerHtml'] = $identifier;
            $this->sources[$source] = [
                'identifier' => $identifier,
                'route' => $sourceRoute
            ];
        }
    }

    private function initializeSources(): void
    {
        $this->sources = [];
    }

    private function getDiameterKeyValue(string $diameter): array
    {
        return [
            'key' => $this->locale->get('wqivyh19'),
            'value' => ['lang' => '', 'value' => $diameter, 'type' => 'text']
        ];
    }

    private function getObverseKeyValue(string $uuid): array
    {
        $currentLanguage = $this->locale->language();
        $lang = $currentLanguage == 'zh' ? '' : 'zh';
        $inscription = $this->databaseTransformer->inscription($uuid);
        return [
            'key' => $this->locale->get('l5aiuo88'),
            'value' => ['lang' => $lang, 'value' => $inscription, 'type' => 'text']
        ];
    }

    private function getReverseKeyValue(string $uuid): array
    {
        $inscription = $this->databaseTransformer->inscription($uuid);
        $currentLanguage = $this->locale->language();
        $lang = $currentLanguage == 'zh' ? '' : 'zh';
        return [
            'key' => $this->locale->get('obs48nh5'),
            'value' => ['lang' => $lang, 'value' => $inscription, 'type' => 'text']
        ];
    }

    private function getVariantKeyValue(string $variant): array
    {
        return [
            'key' => $this->locale->get('hvtmjtxa'),
            'value' => ['lang' => '', 'value' => $variant, 'type' => 'text']
        ];
    }

    private function getWeightKeyValue(string $weight): array
    {
        return [
            'key' => $this->locale->get('k54twvam'),
            'value' => ['lang' => '', 'value' => $weight, 'type' => 'text']
        ];
    }
}
