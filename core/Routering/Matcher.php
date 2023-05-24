<?php

declare(strict_types=1);

namespace Core\Routering;

use Core\Requesting\Request;

class Matcher
{
    /**
     * Match the incoming uri from the client request with the routes.
     * 
     * @case-one: if thereis matches the method will return array of matchs.
     * @case-two: if no matches the method will return empty array.
     *
     * @param array $routes
     * @param Request $request
     * @return void
     */
    public static function match(array $routes, Request $request): array
    {
        $matchedRoute = null;

        $matches = [];
        
        foreach ($routes as $routePattern) {
            $routePattern = $routePattern["path"];

            if (empty($routePattern) || strpos($routePattern, '#') === 0) {
                continue;
            }

            $routePattern = str_replace('{id}', '(\d+)', $routePattern);
            
            if (preg_match("~^" . $routePattern . "$~", $request->getUri(), $matches)) {
                $matchedRoute = $routePattern;
                break;
            }
        }

        return $matches;
    }
}