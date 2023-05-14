<?php

declare(strict_types=1);

require_once __DIR__ . "/../vendor/autoload.php";
require_once __DIR__ . "/../routes/web.php";

Dotenv\Dotenv::createUnsafeImmutable(__DIR__ . '/../')->load();
