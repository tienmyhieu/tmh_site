<?php

namespace lib\transformers;

use lib\core\TmhLocale;
use lib\core\TmhRoute;

class TmhRouteTransformer
{
    private const array EMPEROR_TYPES = ['metal_emperor', 'metal_emperor_coin'];

    public function __construct(private TmhLocale $locale, private TmhRoute $route)
    {
    }

    public function transform(array $entity, string $type): array
    {
        $baserUrl = $_SERVER['REQUEST_SCHEME'] . '://' . $_SERVER['SERVER_NAME'] . '/';
        $entityRoute = $this->route->get($entity['entity']);
        $last = $entityRoute['href'][count($entityRoute['href']) - 1];
        if (in_array($type, self::EMPEROR_TYPES)) {
            $secondLast = $entityRoute['href'][count($entityRoute['href']) - 2];
            $title = implode(' ', $this->locale->getMany([$secondLast, $last]));
            $innerHtml = $this->locale->get($last);
        } else {
            $title = $this->locale->get($last);
            $innerHtml = $title;
        }
        return [
            'code' => $entityRoute['code'],
            'href' => $baserUrl . str_replace(' ', '_', implode('/', $this->locale->getMany($entityRoute['href']))),
            'innerHtml' => $innerHtml,
            'title' => $title,
            'type' => $entity['type']
        ];
    }
}
