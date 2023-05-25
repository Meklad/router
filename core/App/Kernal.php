<?php

declare(strict_types=1);

namespace Core\App;

use Core\App\Container;
use Core\Requesting\Request;
use Core\Routering\{
    Router,
    Matcher
};

/**
 * This class boot the app.
 */
class Kernal
{
    /**
     * DI-Container
     *
     * @var Container
     */
    public Container $container;

    /**
     * Kernal Constructor.
     */
    public function __construct()
    {
        $this->container = new Container();

        $this->container->set(Router::class, function(Container $container) {
            return new Router(
                $container->get(Request::class),
                $container->get(Matcher::class)
            );
        });

        $this->container->set(Request::class, fn() => new Request);

        $this->container->set(Matcher::class, fn() => new Matcher);
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