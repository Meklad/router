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
    public function match(array $routes, Request $request): mixed
    {
        $matchedRoute = null;
        $matches = [];
        $urlParts = parse_url($request->getUri());
    
        $originalRoute = $this->matchUriParams($routes, $urlParts['path'], $matchedRoute, $matches);
        $this->filterQueryString($matchedRoute, $urlParts['query'], $matches);
        
        if(empty($matches["params"])) {
            return null;
        }

        $matches["route"] = $originalRoute;
        return $matches;
    }

    /**
     * Match url parameters.
     *
     * @param array $routes
     * @param string $path
     * @param null|string $matchedRoute
     * @param array $matches
     * @return void
     */
    private function matchUriParams(array $routes, string $path, null|string &$matchedRoute, array &$matches)
    {
        $originalRoute = null;

        foreach ($routes as $routePattern) {
            $originalRoute = $routePattern;
            $routePattern = $routePattern["path"];
    
            if (empty($routePattern) || strpos($routePattern, '#') === 0) {
                continue;
            }
    
            $routePattern = str_replace('{id}', '(\d+)', $routePattern);
    
            if (preg_match("~^" . $routePattern . "$~", $path, $matches["params"])) {
                $matchedRoute = $routePattern;
                return $originalRoute;
            }
        }
    }

    /**
     * Filter request query. string
     *
     * @param null|string $matchedRoute
     * @param null|string $queryString
     * @param array $matches
     * @return void
     */
    private function filterQueryString(null|string &$matchedRoute, null|string &$queryString, array &$matches): void
    {
        if ($matchedRoute !== null) {
            if($queryString != null) {
                parse_str($queryString, $query);
                $sanitizedQuery = [];
        
                foreach ($query as $key => $value) {
                    $sanitizedKey = urldecode($key);
                    $sanitizedValue = urldecode($value);
            
                    if ($sanitizedKey !== '' && $sanitizedValue !== '') {
                        $sanitizedQuery[$sanitizedKey] = $sanitizedValue;
                    }
                }
            
                $matches['query'] = $sanitizedQuery;
            }
        }
    }
}