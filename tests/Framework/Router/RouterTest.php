<?php

namespace  Tests\Framework\Router;

use Framework\Router\Router;
use GuzzleHttp\Psr7\ServerRequest;
use PHPUnit\Framework\TestCase;

class RouterTest extends TestCase
{

    private $router;

    public function setUp(): void
    {
        $this->router = new Router();
    }

    public function testGetMethod()
    {
        $request = new ServerRequest('GET', '/');
        $this->router->get(
            '/',
            function () {
                return "Hello world";
            },
            'route.name'
        );
        $route = $this->router->match($request);
        $this->assertEquals('route.name', $route->getName());
        $this->assertEquals('Hello world', call_user_func($route->getCallback()));
        $this->assertEquals([], $route->getParams());
    }

    public function testGetMethodWithInvalidUrl()
    {
        $request = new ServerRequest('GET', '/azeaze');
        $this->router->get(
            '/',
            function () {
                return "Hello world";
            },
            'route.name'
        );
        $route = $this->router->match($request);
        $this->assertEquals(null, $route);
    }

    public function testGetMethodWithParams()
    {
        $request = new ServerRequest('GET', '/test/10/toto');
        $this->router->get(
            '/test/[i:id]/[a:name]',
            function () {
                return "Hello world";
            },
            'route.name'
        );
        $route = $this->router->match($request);
        $this->assertArrayHasKey('id', $route->getParams());
        $this->assertEquals(10, $route->getParams()['id']);
        $this->assertArrayHasKey('name', $route->getParams());
        $this->assertEquals('toto', $route->getParams()['name']);
    }
}
