<?php declare(strict_types = 1);

namespace Framework\Router;

use AltoRouter;
use Framework\Exception\RouteNotMatchedException;
use Psr\Http\Message\ServerRequestInterface;

class Router
{

    /**
     * @var AltoRouter
     */
    private AltoRouter $router;

    public function __construct()
    {
        $this->router = new AltoRouter();
    }

    /**
     * @param string $uri
     * @param string|array<string, string> $callback
     * @param string $name
     * @throws \Exception
     */
    public function get(string $uri, $callback, string $name): void
    {
        $this->router->map('GET', $uri, $callback, $name);
    }

    /**
     * @param ServerRequestInterface $httpRequest
     * @return Route|null
     */
    public function match(ServerRequestInterface $httpRequest): ?Route
    {
        /**
         * @var false|array{
         *      name: string,
         *      target: callable|string|array<string, string>,
         *      params:array<string, mixed|null>
         * } $match
         */
        $match = $this->router->match($httpRequest->getUri()->getPath(), $httpRequest->getMethod());

        if ($match === false) {
            return null;
        }

        return new Route(
            $match['name'],
            $httpRequest->getUri()->getPath(),
            $match['target'],
            $match['params'] ?? []
        );
    }
}
