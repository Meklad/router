<?php

use App\Controllers\HomeController;
use App\Controllers\UserController;

$router->get("/", [HomeController::class, "index"]);

$router->get("/about-us", function() {
    echo "About Us";
});

$router->get("/contact-us", function() {
    echo "Contact Us";
});

$router->get("/users/{id}", [UserController::class, "show"]);

$router->get("/users/{id}/edit", function(string $id) {
    echo "ID: " . $id;
});
