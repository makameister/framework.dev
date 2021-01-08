<?php

namespace Tests\Framework\Middleware;

use Framework\Middleware\TrailingSlashMiddleware;
use GuzzleHttp\Psr7\ServerRequest;
use PHPUnit\Framework\TestCase;
use Tests\Resources\Helpers;

class TrailingSlashMiddlewareTest extends TestCase
{

    private $middleware;

    private $requestHandler;

    use Helpers;

    public function setUp(): void
    {
        $this->middleware = new TrailingSlashMiddleware();
        $this->requestHandler = $this->makeRequestHandler();
    }

    public function testRequestIsRedirected()
    {
        $request = new ServerRequest('GET', '/test/');
        $response = $this->middleware->process($request, $this->requestHandler->reveal());

        $this->assertEquals(301, $response->getStatusCode());
        $this->assertEquals("/test", $response->getHeaderLine('Location'));
    }

    public function testRequestIsNotRedirected()
    {
        $request = new ServerRequest('GET', '/test');
        $response = $this->middleware->process($request, $this->requestHandler->reveal());

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals("", $response->getHeaderLine('Location'));
    }

}