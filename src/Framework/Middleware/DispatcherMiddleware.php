<?php declare(strict_types = 1);

namespace Framework\Middleware;

use Exception;
use Framework\Http\Response;
use Framework\Router\Route;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class DispatcherMiddleware implements MiddlewareInterface
{
    /**
     * @var ContainerInterface
     */
    private ContainerInterface $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $route = $request->getAttribute(Route::class);
        if (is_null($route)) {
            return $handler->handle($request);
        }

        $this->container->set(ServerRequestInterface::class, $request);

        $callback = $route->getCallback();
        if (is_array($callback)) {
            $response = $this->container->call([
                $this->container->get($callback[0]),
                $callback[1]
            ]);
        } elseif (is_string($callback)) {
            $response = $this->container->call(
                $this->container->get($callback)
            );
        } else {
            throw new Exception("Le callback '$callback' ne peut pas être résolue");
        }

        if (is_string($response)) {
            return new Response(200, [], $response);
        } elseif ($response instanceof ResponseInterface) {
            return $response;
        } else {
            throw new Exception('The response is not a string or an instance of ResponseInterface');
        }
    }
}
