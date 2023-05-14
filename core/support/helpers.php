<?php

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