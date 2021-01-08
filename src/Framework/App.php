<?php declare(strict_types=1);

namespace Framework;

use DI\ContainerBuilder;
use Framework\Http\Response;
use Psr\Container\ContainerInterface;
use Psr\EventDispatcher\EventDispatcherInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class App implements RequestHandlerInterface
{

    /**
     * @var string : path to global configuration
     */
    private string $config;

    /**
     * @var ContainerInterface|null : dependency injection container
     */
    private ?ContainerInterface $container;

    /**
     * @var array<string> : list of application modules
     */
    private array $modules;

    /**
     * @var array<string|Object> : list of middlewares
     */
    private array $middlewares;

    /**
     * @var int
     */
    private int $index = 0;

    public function __construct(string $config)
    {
        $this->config = $config;
        $this->container = null;
    }

    /**
     * Add a module to application
     * @param string $module
     * @return self
     */
    public function addModule(string $module): self
    {
        $this->modules[] = $module;
        return $this;
    }

    /**
     * Add a middleware
     * @param string $middleware
     * @return $this
     */
    public function pipe(string $middleware): self
    {
        $this->middlewares[] = $middleware;
        return $this;
    }

    /**
     * Launch application
     * @param ServerRequestInterface $httpRequest
     * @return ResponseInterface
     * @throws \Exception
     */
    public function run(ServerRequestInterface $httpRequest): ResponseInterface
    {
        foreach ($this->modules as $module) {
            $this->getContainer()->get($module);
        }
        return $this->handle($httpRequest);
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $middleware = $this->getMiddleware();
        if (is_null($middleware)) {
            return $this->handle($request);
        }
        return $middleware->process($request, $this);
    }

    /**
     * Initialize app container
     * @return ContainerInterface
     * @throws \Exception
     */
    public function getContainer(): ContainerInterface
    {
        if ($this->container === null) {
            $builder = new ContainerBuilder();
            $builder->addDefinitions($this->config);
            $builder->useAnnotations(true);
            foreach ($this->modules as $module) {
                if ($module::CONFIG) {
                    $builder->addDefinitions($module::CONFIG);
                }
            }
            $this->container = $builder->build();
        }
        return $this->container;
    }

    /**
     * @return MiddlewareInterface|null
     * @throws \Exception
     */
    private function getMiddleware(): ?MiddlewareInterface
    {
        if (array_key_exists($this->index, $this->middlewares)) {
            if (is_string($this->middlewares[$this->index])) {
                $middleware = $this->getContainer()->get($this->middlewares[$this->index]);
            } else {
                $middleware = $this->middlewares[$this->index];
            }
            $this->index++;
            return $middleware;
        }
        return null;
    }

    /**
     * @return array<string>
     */
    public function getModules(): array
    {
        return $this->modules;
    }
}
