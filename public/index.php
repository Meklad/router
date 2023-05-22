<?php

declare(strict_types=1);

use Core\Requesting\Request;
use Core\Routering\Router;

require_once __DIR__ . "/../vendor/autoload.php";

Dotenv\Dotenv::createUnsafeImmutable(__DIR__ . '/../')->load();

$request = Request::bootstrapRequestComponents($_SERVER);

$router = new Router($request);
