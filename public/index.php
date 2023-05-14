<?php

require_once __DIR__ . "/../vendor/autoload.php";

Dotenv\Dotenv::createUnsafeImmutable(__DIR__ . '/../')->load();


dump($_ENV);
