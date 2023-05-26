<?php

declare(strict_types=1);

namespace Tests\Unit;

use Core\Routering\Router;
use Core\Routering\Matcher;
use Core\Requesting\Request;
use PHPUnit\Framework\TestCase;
use App\Controllers\HomeController;
use Core\Requesting\RequestInterface;

class RequestTest extends TestCase
{
    /**
     * Create new request.
     *
     * @return Request
     */
    private function createNewRequest(): RequestInterface
    {
        return new Request($this->simulateServerGlobalVar());
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

    /** @test */
    public function it_request_can_set_http(): void
    {
        $request = $this->createNewRequest();

        $this->assertEquals(
            $this->simulateServerGlobalVar()["HTTPS"],
            $request->isSecureRequest()
        );
    }

    /** @test */
    public function it_request_can_set_host_name(): void
    {
        $request = $this->createNewRequest();
        $this->assertEquals(
            $this->simulateServerGlobalVar()["HTTP_HOST"],
            $request->getCleanDomain()
        );
    }

    /** @test */
    public function it_request_can_set_full_url(): void
    {
        $request = $this->createNewRequest();
        $this->assertEquals(
            "https://" . $this->simulateServerGlobalVar()["HTTP_HOST"] . "/",
            $request->getFullUrl()
        );
    }

    /** @test */
    public function it_request_can_set_http_method(): void
    {
        $request = $this->createNewRequest();
        $this->assertEquals(
            $this->simulateServerGlobalVar()["REQUEST_METHOD"],
            $request->getHttpMethod()
        );
    }

    /** @test */
    public function it_request_can_set_server_name(): void
    {
        $request = $this->createNewRequest();
        $this->assertEquals(
            $this->simulateServerGlobalVar()["SERVER_NAME"],
            $request->getCleanDomain()
        );
    }

    /** @test */
    public function it_request_can_set_uri(): void
    {
        $request = $this->createNewRequest();
        $this->assertEquals(
            $this->simulateServerGlobalVar()["REQUEST_URI"],
            $request->getUri()
        );
    }

    /** @test */
    public function it_request_can_set_url_params(): void
    {
        $request = $this->createNewRequest();
        $this->assertEquals(
            $this->simulateServerGlobalVar()["toAssetUrlParams"],
            $request->getUrlParams()
        );
    }
}