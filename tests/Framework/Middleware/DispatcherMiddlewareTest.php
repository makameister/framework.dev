<?php

namespace Tests\Framework\Middleware;

use Exception;
use Framework\Http\HttpRequest;
use Framework\Middleware\DispatcherMiddleware;
use Framework\Router\Route;
use PHPUnit\Framework\TestCase;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Tests\Resources\Controller\TestController;
use Tests\Resources\Helpers;

class DispatcherMiddlewareTest extends TestCase
{

    private $middleware;

    private $requestHandler;

    use Helpers;

    private function makeSimpleContainer(): void
    {
        $container = $this->getMockBuilder(ContainerInterface::class)
            ->getMock();
        $this->middleware = new DispatcherMiddleware($container);
    }

    private function makeContainerWithCallback()
    {
        $container = $this->getMockBuilder(ContainerInterface::class)
            ->getMock();
        $container->expects($this->once())
            ->method('get')
            ->willReturn(new TestController());
        $this->middleware = new DispatcherMiddleware($container);
    }

    public function testWithNullRoute()
    {
        $this->makeSimpleContainer();

        $request = new HttpRequest('GET', '/test');
        $request->withAttribute(Route::class, null);

        $requestHandler = $this->getMockBuilder(RequestHandlerInterface::class)
            ->getMock();
        $requestHandler->expects($this->once())
            ->method('handle')
            ->with($this->callback(function (ServerRequestInterface $request) {
                return $request->getAttribute(Route::class) === null;
            }));

        $this->middleware->process($request, $requestHandler);
    }

    public function testWithArrayCallback()
    {
        $this->makeContainerWithCallback();

        $request = new HttpRequest('GET', '/test');
        $request = $request->withAttribute(
            Route::class,
            new Route('route.test', '/test', [TestController::class, 'stringResponse'])
        );

        $requestHandler = $this->getMockBuilder(RequestHandlerInterface::class)
            ->getMock();

        $response = $this->middleware->process($request, $requestHandler);

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals("Hello world 2", $response->getBody());
    }

    public function testWithStringCallback()
    {
        $this->makeContainerWithCallback();

        $request = new HttpRequest('GET', '/test');
        $request = $request->withAttribute(
            Route::class,
            new Route('route.test', '/test', TestController::class)
        );

        $requestHandler = $this->getMockBuilder(RequestHandlerInterface::class)
            ->getMock();

        $response = $this->middleware->process($request, $requestHandler);

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals("Hello world", $response->getBody());
    }

    public function testWithBadCallback()
    {
        $this->makeSimpleContainer();

        $request = new HttpRequest('GET', '/test');
        $request = $request->withAttribute(
            Route::class,
            new Route('route.test', '/test', 10)
        );

        $requestHandler = $this->getMockBuilder(RequestHandlerInterface::class)
            ->getMock();

        $this->expectException(Exception::class);
        $this->expectExceptionMessage("Le callback '10' ne peut pas être résolue");

        $this->middleware->process($request, $requestHandler);
    }

    public function testWithResponseInterfaceReturn()
    {
        $this->makeContainerWithCallback();

        $request = new HttpRequest('GET', '/test');
        $request = $request->withAttribute(
            Route::class,
            new Route('route.test', '/test', [TestController::class, 'testResponse'])
        );

        $requestHandler = $this->getMockBuilder(RequestHandlerInterface::class)
            ->getMock();

        $response = $this->middleware->process($request, $requestHandler);

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals("Hello world 3", $response->getBody());
    }

    public function testWithBadReturn()
    {
        $this->makeContainerWithCallback();

        $request = new HttpRequest('GET', '/test');
        $request = $request->withAttribute(
            Route::class,
            new Route('route.test', '/test', [TestController::class, 'badResponse'])
        );

        $requestHandler = $this->getMockBuilder(RequestHandlerInterface::class)
            ->getMock();

        $this->expectException(Exception::class);
        $this->expectExceptionMessage('The response is not a string or an instance of ResponseInterface');

        $this->middleware->process($request, $requestHandler);
    }

}
