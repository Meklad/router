<?php

declare(strict_types=1);

namespace Core;

use App\Exceptions\RouteNotFoundException;

class Router
{
    private array $_routes = [];

    private const GET_HTTP_METHOD = "GET";

    private const POST_HTTP_METHOD = "POST";

    public function get(string $path, callable|array $callback): void
    {
        $this->_storeRoutes(self::GET_HTTP_METHOD, $path, $callback);
    }

    public function post(string $path, callable|array $callback): void
    {
        $this->_storeRoutes(self::POST_HTTP_METHOD, $path, $callback);
    }

    private function _storeRoutes(string $method, string $path, callable|array $callback): void
    {
        $this->_routes[$method . $path] = [
            "path" => $path,
            "callback" => $callback
        ];
    }

    public function run()
    {
        $requestUri = parse_url($_SERVER["REQUEST_URI"]);
        
        $requestPath = $requestUri["path"];

        $uriComponents = explode("/", ltrim($requestPath, "/"));

        $requestMethod = $_SERVER["REQUEST_METHOD"];

        $route = $requestMethod . $requestPath;

        $callback = isset($this->_routes[$route]["callback"]) ? $this->_routes[$route]["callback"] : null;

        try {
            if(!$callback) {
                throw new RouteNotFoundException();
            }

            if(is_array($callback)) {
                if(class_exists($callback[0])) {
                    if(method_exists($callback[0], $callback[1])) {
                        $controller = new $callback[0];
                        $method = $callback[1];
                        $callback = [$controller,$method];
                    }
                }
            }

            if(is_callable($callback)) {
                call_user_func_array($callback, [array_merge($_GET, $_POST)]);
            }
        } catch(RouteNotFoundException $e) {
            return view("404", ["exception" => $e->getMessage()]);
        }
    }
}