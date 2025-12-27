<?php

namespace lib\core;

readonly class TmhRoute
{
    private string $route;
    private array $routes;

    public function __construct(private TmhJson $json)
    {
        $this->routes = $this->json->routes();
        $this->initializeRoute();
    }

    public function route(): string
    {
        return $this->route;
    }

    public function routes(): array
    {
        return $this->routes;
    }

    private function initializeRoute(): void
    {
        parse_str($_SERVER['REDIRECT_QUERY_STRING'], $fields);
        $this->route = $fields['title'];
    }
}
