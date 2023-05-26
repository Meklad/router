<?php

declare(strict_types=1);

namespace Core\Routering;

use Core\Routering\Matcher;
use Core\Requesting\RequestInterface;
use App\Exceptions\RouteNotFoundException;

class Router
{
    /**
     * GET http method.
     */
    private const GET_HTTP_METHOD = "GET";

    /**
     * POST http method.
     */
    private const POST_HTTP_METHOD = "POST";

    /**
     * Array of routes imported from web.php
     *
     * @var array
     */
    private array $routes;

    /**
     * Array of routes imported from web.php without callback.
     *
     * @var array
     */
    public array $routesWithoutCallback;

    /**
     * Router Constructor.
     *
     * @param RequestInterface $requst
     * @param Matcher $matcher
     */
    public function __construct(
        private RequestInterface $requst,
        private Matcher $matcher
    ){}

    /**
     * Set a route called by get http method.
     *
     * @param string $path
     * @param callable|array $callback
     * @return void
     */
    public function get(string $path, callable|array $callback): void
    {
        $this->setRoute($path, $callback, self::GET_HTTP_METHOD);
    }

    /**
     * Set a route called by post http method.
     *
     * @param string $path
     * @param callable|array $callback
     * @return void
     */
    public function post(string $path, callable|array $callback): void
    {
        $this->setRoute($path, $callback, self::POST_HTTP_METHOD);
    }

    /**
     * Set the given route that came from the request.
     *
     * @param string $path
     * @param callable|array $callback
     * @param string $httpMethod
     * @return void
     */
    private function setRoute(string $path, callable|array $callback, string $httpMethod): void
    {
        $this->routes[$path] = [
            "path" => $path,
            "http_method" => $httpMethod,
            "callback" => $callback
        ];

        $this->routesWithoutCallback[$path] = [
            "path" => $path,
            "http_method" => $httpMethod
        ];
    }

    /**
     * Load the callback wither if it function or controller method.
     *
     * @return void
     */
    public function load()
    {
        $matches = $this->matcher->match($this->routes, $this->requst);
        
        if(empty($matches)) {
            return view("404", ["exception" => "The Requested Route Is Not Found..."]);
            exit;
        }
        
        $route = $matches["route"];
        $callback = $route["callback"];
        $methodParam = isset($matches["params"][1]) ? $matches["params"][1] : null;

        try {
            if(!$callback) {
                throw new RouteNotFoundException();
            }

            if(is_array($callback)) {
                if(class_exists($callback[0])) {
                    if(method_exists($callback[0], $callback[1])) {
                        $controller = app()->container->get($callback[0]);
                        $method = $callback[1];
                        $callback = [$controller,$method];
                    }
                }
            }

            if(is_callable($callback)) {
                call_user_func($callback, $methodParam);
            }
        } catch(RouteNotFoundException $e) {
            return view("404", ["exception" => $e->getMessage()]);
        }
    }

    /**
     * Return array of registerd routes.
     *
     * @return array
     */
    public function getRoutes(): array
    {
        return $this->routes;
    }

    /**
     * Return array of registerd routes.
     *
     * @return array
     */
    public function getRoutesWithoutCallaback(): array
    {
        return $this->routesWithoutCallback;
    }
}