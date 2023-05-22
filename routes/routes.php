<?php

use Core\Routering\Router;
use App\Controllers\HomeController;

$router = new Router();

$router->get("/", [HomeController::class, "index"]);

$router->get("/about-us", function() {
    echo "About Us";
});

$router->get("/contact-us", function() {
    echo "Contact Us";
});

$router->get("/users/1", function(string $username) {
    echo "Username Is: " . $username;
});

$router->run();