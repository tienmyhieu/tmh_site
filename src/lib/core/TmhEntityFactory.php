<?php

namespace lib\core;

readonly class TmhEntityFactory
{
    public function __construct(private TmhJson $json, private TmhRouteFactory $routeFactory)
    {
    }

    public function create(): array
    {
        $route = $this->routeFactory->create();
        $entity = $this->json->entity('/' . str_replace('.', '/', $route['code']), $route['entity']);
        if (!count($entity)) {
            $route = $this->routeFactory->parent();
            $entity = $this->json->entity('/' . str_replace('.', '/', $route['code']), $route['entity']);
        }
        $isGroup = 4 == strlen($route['entity']);
        $entity['type'] = $isGroup ? 'metal_emperor_coin_group' : $route['entity'];
        return $entity;
    }
}
