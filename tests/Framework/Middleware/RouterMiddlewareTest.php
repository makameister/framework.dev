<?php

namespace Tests\Framework\Middleware;

use Framework\Http\HttpRequest;
use Framework\Middleware\RouterMiddleware;
use Framework\Router\Route;
use Framework\Router\Router;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Tests\Resources\Controller\TestController;
use Tests\Resources\Helpers;

class RouterMiddlewareTest extends TestCase
{

    private $middleware;

    private $requestHandler;

    use Helpers;

    protected function setUp(): void
    {
        $router = new Router();
        $router->get('/test', TestController::class, 'route.test');
        $router->get('/test/[i:id]/[a:name]', TestController::class, 'route.test.params');
        $this->middleware = new RouterMiddleware($router);
    }

    public function testRouteIsMatched()
    {
        $request = new HttpRequest('GET', '/test');

        $requestHandler = $this->getMockBuilder(RequestHandlerInterface::class)
            ->getMock();
        $requestHandler->expects($this->once())
            ->method('handle')
            ->with($this->callback(function (ServerRequestInterface $request) {
                return $request->getAttribute(Route::class) instanceof Route;
            }));

        $this->middleware->process($request, $requestHandler);
    }

    public function testRouteHasParams()
    {
        $request = new HttpRequest('GET', '/test/10/toto');

        $requestHandler = $this->getMockBuilder(RequestHandlerInterface::class)
            ->getMock();
        $requestHandler->expects($this->once())
            ->method('handle')
            ->with($this->callback(function (ServerRequestInterface $request) {
                return $request->getAttribute('id') === '10' && $request->getAttribute('name') === 'toto';
            }));

        $this->middleware->process($request, $requestHandler);
    }

    public function testRouteIsNotMatched()
    {
        $request = new HttpRequest('GET', '/toto');

        $requestHandler = $this->getMockBuilder(RequestHandlerInterface::class)
            ->getMock();
        $requestHandler->expects($this->once())
            ->method('handle')
            ->with($this->callback(function (ServerRequestInterface $request) {
                return $request->getAttribute(Route::class) === null;
            }));

        $this->middleware->process($request, $requestHandler);
    }
}
