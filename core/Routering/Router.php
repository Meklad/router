<?php

declare(strict_types=1);

namespace Core\Routering;

use App\Exceptions\RouteNotFoundException;
use Core\Requesting\Request;

class Router
{
    private array $routes;

    public function __construct(private Request $requst){}

    public function get(string $path, callable|array $callback)
    {
        $this->routes[$path] = [
            "path" => $path,
            "http_method" => "GET",
            "callback" => $callback
        ];
    }

    public function post(string $path, callable|array $callback)
    {
        $this->routes[$path] = [
            "path" => $path,
            "http_method" => "GET",
            "callback" => $callback
        ];
    }

    public function run()
    {
        $matches = $this->match();
        
        if(count($matches) === 0) {
            return view("404", ["exception" => "The Requested Route Is Not Found..."]);
            exit;
        }

        dd($matches);
    }

    public function match()
    {            
        $matchedRoute = null;

        $matches = [];

        foreach ($this->routes as $routePattern) {
            $routePattern = $routePattern["path"];

            if (empty($routePattern) || strpos($routePattern, '#') === 0 || $routePattern === "/") {
                continue;
            }

            $routePattern = str_replace('{id}', '(\d+)', $routePattern);
            
            if (preg_match("~^" . $routePattern . "$~", $this->requst->getUri(), $matches)) {
                $matchedRoute = $routePattern;
                break;
            }
        }

        if ($matchedRoute !== null) {
            return $matches;
        }

        return [];
    }
}