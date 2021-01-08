<?php

namespace  Tests\Framework\Router;

use Framework\Router\Route;
use PHPUnit\Framework\TestCase;
use Tests\Resources\Controller\TestController;
use Tests\Resources\Helpers;

class RouteTest extends TestCase
{

    private Route $route;

    use Helpers;

    public function __construct()
    {
        parent::__construct();

        $this->route = new Route(
            'route.name',
            '/uri',
            TestController::class,
            [
            "id" => 10,
            "name" => "toto"
            ]
        );
    }

    public function testRouteHasName()
    {
        $this->assertEquals('route.name', $this->route->getName());
    }

    public function testRouteHasUri()
    {
        $this->assertEquals('/uri', $this->route->getUri());
    }

    public function testRouteCallback()
    {
        $this->assertEquals(TestController::class, $this->route->getCallback());
    }

    public function testRouteHasParams()
    {
        $params = $this->route->getParams();
        $this->assertArrayHasKey("id", $params);
        $this->assertArrayHasKey("name", $params);
    }
}
