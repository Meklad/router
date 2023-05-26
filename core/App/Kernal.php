<?php

declare(strict_types=1);

namespace Core\App;

use Core\App\Container;
use Core\Routering\Router;
use Core\Requesting\Request;
use Core\Requesting\RequestInterface;

/**
 * This class boot the app.
 */
class Kernal
{
    /**
     * Kernal Constructor.
     * 
     * @param Container $container
     */
    public function __construct(public Container $container)
    {
        $this->container->set(RequestInterface::class, function () {
            return new Request($_SERVER);
        });
    }

    /**
     * Boot The app.
     *
     * @return void
     */
    public function boot(): void
    {
        $this->bootRouter();
    }

    /**
     * Boot Router.
     *
     * @return void
     */
    public function bootRouter(): void
    {
        $router = $this->container->get(Router::class);
        require_once __DIR__ . "/../../routes/web.php";
        $router->load();
    }
}