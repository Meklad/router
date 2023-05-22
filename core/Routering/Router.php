<?php

declare(strict_types=1);

namespace Core\Routering;

use Core\Requesting\Request;

class Router
{
    public function __construct(Request $requst)
    {
        dd($requst);
    }
    public function get(string $path, callable|array $callback)
    {

    }

    public function post(string $path, callable|array $callback)
    {

    }

    public function run()
    {

    }
}