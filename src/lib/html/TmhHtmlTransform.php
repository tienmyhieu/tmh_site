<?php

namespace lib\html;

require_once('TmhHtmlEmperorCoin.php');
require_once('TmhHtmlListEntity.php');
require_once('TmhHtmlMetalCoin.php');
require_once('TmhHtmlSpecimen.php');

class TmhHtmlTransform
{
    public function transform(array $entity): void
    {
        $transformer = match($entity['type']) {
            'toc',
            'metal',
            'metal_emperor' => new TmhHtmlListEntity(),
            'metal_coin' => new TmhHtmlMetalCoin(),
            'metal_emperor_coin_group',
            'metal_emperor_coin' => new TmhHtmlEmperorCoin(),
            default => new TmhHtmlSpecimen()
        };
        $transformer->transform($entity);
    }
}
