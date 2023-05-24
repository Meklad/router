<?php

use Core\Routering\Router;
use Core\Requesting\Request;
use App\Controllers\HomeController;
use Core\Routering\Matcher;

$router = new Router(
    Request::bootstrapRequestComponents($_SERVER),
    new Matcher
);

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

$router->load();