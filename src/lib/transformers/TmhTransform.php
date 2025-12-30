<?php

namespace lib\transformers;

use lib\core\TmhLocale;

require_once('TmhEmperorCoin.php');
require_once('TmhListEntity.php');
require_once('TmhMetalCoin.php');
require_once('TmhRouteTransformer.php');
require_once('TmhSpecimen.php');

readonly class TmhTransform
{
    public function __construct(
        private TmhDatabaseTransformer $databaseTransformer,
        private TmhLocale $locale,
        private TmhRouteTransformer $routeTransformer
    ) {
    }

    public function transform(array $entity): array
    {
        $transformer = match($entity['type']) {
            'toc',
            'metal',
            'metal_emperor' => new TmhListEntity($this->locale, $this->routeTransformer),
            'metal_coin' => new TmhMetalCoin($this->databaseTransformer, $this->locale, $this->routeTransformer),
            'metal_emperor_coin_group',
            'metal_emperor_coin' => new TmhEmperorCoin(
                $this->databaseTransformer,
                $this->locale,
                $this->routeTransformer
            ),
            default => new TmhSpecimen($this->databaseTransformer, $this->locale, $this->routeTransformer)
        };
        return $transformer->transform($entity);
    }
}
