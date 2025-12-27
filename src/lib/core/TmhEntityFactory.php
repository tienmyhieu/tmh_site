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
        $entity = $this->json->entity('/' .str_replace('.', '/', $route['code']), $route['entity']);
        if (!count($entity)) {
            $route = $this->routeFactory->parent();
            $entity = $this->json->entity('/' .str_replace('.', '/', $route['code']), $route['entity']);
        }
        return $entity;
    }
}
