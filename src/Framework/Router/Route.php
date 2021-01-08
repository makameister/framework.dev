<?php declare(strict_types = 1);

namespace Framework\Router;

class Route
{

    /**
     * @var string : route name
     */
    private string $name;

    /**
     * @var string : route uri
     */
    private string $uri;

    /**
     * @var callable|string|array<string, string>
     */
    private $callback;

    /**
     * @var array<string, mixed|null> : route parameter(s)
     */
    private array $params;

    /**
     * Route constructor.
     * @param string $name
     * @param string $uri
     * @param callable|string|array<string, string> $callback
     * @param array<string, mixed|null> $params
     */
    public function __construct(string $name, string $uri, $callback, array $params = [])
    {
        $this->name = $name;
        $this->uri = $uri;
        $this->callback = $callback;
        $this->params = $params;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getUri(): string
    {
        return $this->uri;
    }

    /**
     * @return callable|string|array<string, string>
     */
    public function getCallback()
    {
        return $this->callback;
    }

    /**
     * @return array<string, mixed|null>
     */
    public function getParams(): array
    {
        return $this->params;
    }
}
