<?php

declare(strict_types=1);

use Core\App\{
    Kernal,
    Container
};

require_once __DIR__ . "/../vendor/autoload.php";

Dotenv\Dotenv::createUnsafeImmutable(__DIR__ . '/../')->load();

$app = new Kernal(new Container);

$app->boot();