<?php

declare(strict_types=1);

use Core\App\{
    Kernal,
    Container
};

require_once __DIR__ . "/../vendor/autoload.php";

Dotenv\Dotenv::createUnsafeImmutable(__DIR__ . '/../')->load();
$container = new Container;
$app = new Kernal($container);
$app->boot();