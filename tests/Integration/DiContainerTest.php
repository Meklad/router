<?php

declare(strict_types=1);

namespace Tests\Unit;

use Core\App\Kernal;
use Core\App\Container;
use Core\Requesting\Request;
use Core\Requesting\RequestInterface;
use Core\Routering\Matcher;
use Core\Routering\Router;
use PHPUnit\Framework\TestCase;

class DiContainerTest extends TestCase
{
    /** @test */
    public function it_di_container_has_id(): void
    {
        $app = new Kernal(new Container);
        
        $app->container->set(RequestInterface::class, function () {
            return new Request($this->simulateServerGlobalVar());
        });
        
        $this->assertTrue($app->container->has(RequestInterface::class));
    }

    /**
     * Simulate $_SERVER global var.
     *
     * @return array
     */
    private function simulateServerGlobalVar(): array
    {
        return [
            "HTTPS" => "on",
            "HTTP_HOST" => "mollyera.market",
            "REQUEST_URI" => "/",
            "REQUEST_METHOD" => "GET",
            "SERVER_NAME" => "mollyera.market",
            "REQUEST_URI" => "/",
            "QUERY_STRING" => "log=log",
            "REDIRECT_URL" => "users/1/edit",
            "toAssetUrlParams" => [
                "users",
                "1",
                "edit"
            ]
        ];
    }
}