<?php

namespace lib\core;

readonly class TmhTranslation
{
    private array $locales;
    private string $requestedRoute;
    private array $routeKeys;
    private array $routes;

    public function __construct(private TmhJson $json, private TmhLocale $locale, private TmhRoute $route)
    {
        $this->locales = $this->locale->locales();
        $this->requestedRoute = $this->route->route();
        $this->routes = $this->route->routes();
        $this->initializeRouteKeys();
    }

    public function getEntity(): array
    {
        $entity = null;
        if (in_array($this->requestedRoute, array_keys($this->routeKeys))) {
            $routeKey = $this->routeKeys[$this->requestedRoute];
            $route = $this->routes[$routeKey];
            $entity = $this->json->entity(str_replace('.', '/', $route['code']), $route['entity']);
        }
        if (is_null($entity)) {
            $routeParts = explode('/', $this->requestedRoute);
            $requestedEntity = strtolower($routeParts[count($routeParts) - 1]);
            unset($routeParts[count($routeParts) - 1]);
            $ancestorRoute = implode('/', $routeParts);
            $routeKey = $this->routeKeys[$ancestorRoute];
            $route = $this->routes[$routeKey];
            $entity = $this->json->entity(str_replace('.', '/', $route['code']), $requestedEntity);
            if (empty($entity)) {
                $entity = $this->json->entity(str_replace('.', '/', $route['code']), $route['entity']);
            }
        }
        return $entity;
    }

    private function initializeRouteKeys(): void
    {
        $transformed = [];
        foreach ($this->routes as $routeKey => $route) {
            $key = '';
            foreach ($route['href'] as $href) {
                $key .= str_replace(' ', '_', $this->locales[$href]) . '/';
            }
            $key = substr($key, 0, -1);
            $transformed[$key] = $routeKey;
        }
        $this->routeKeys = $transformed;
    }
}
