<?php

use App\Exceptions\ViewNotFoundException;

if(!function_exists("env")) {
    /**
     * To get value form .env file.
     */
    function env(string $key): string
    {
        if(!is_array($_ENV) || !isset($_ENV[$key])) {
            return null;
        }

        return $_ENV[$key];
    }
}

if(!function_exists("view")) {
    /**
     * Return view file.
     */
    function view(string $path, array $args = [])
    {
        try {
            extract($args);
            require_once __DIR__ . "/../../views/" . $path . ".php";
        } catch(ViewNotFoundException $e) {
            return view("404", ["exception" => $e->getMessage()]);
        }
    }
}