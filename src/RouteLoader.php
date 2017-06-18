<?php

declare(strict_types=1);

namespace Jarvis\Skill\Helper;

use Jarvis\Skill\Routing\Router;

/**
 * @author Eric Chau <eriic.chau@gmail.com>
 */
class RouteLoader
{
    public static function load(Router $router, array $routes = [])
    {
        foreach ($routes as $route) {
            $router
                ->beginRoute($route['name'] ?? null)
                    ->setPattern($route['pattern'])
                    ->setMethod($route['method'] ?? 'get')
                    ->setHandler($route['handler'])
                ->end()
            ;
        }
    }
}
