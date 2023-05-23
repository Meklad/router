<?php

declare(strict_types=1);

use Core\Requesting\Request;
use Core\Routering\Router;

require_once __DIR__ . "/../vendor/autoload.php";

Dotenv\Dotenv::createUnsafeImmutable(__DIR__ . '/../')->load();

$request = Request::bootstrapRequestComponents($_SERVER);

$router = new Router($request);

$router->get("/", [HomeController::class, "index"]);

$router->get("/about-us", function() {
    echo "About Us";
});

$router->get("/contact-us", function() {
    echo "Contact Us";
});

$router->get("/users/{id}", function(string $username) {
    echo "Username Is: " . $username;
});

$router->get("/users/{id}/edit", function(string $username) {
    echo "Username Is: " . $username;
});

$router->post("/users/{id}", function(string $username) {
    echo "Username Is: " . $username;
});

$router->run();