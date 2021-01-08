<?php

namespace App\Home\Controller;

use Framework\Renderer\RendererInterface;
use Psr\EventDispatcher\EventDispatcherInterface;
use Psr\Http\Message\ServerRequestInterface;

class HomeController
{

    /**
     * @var RendererInterface
     */
    private RendererInterface $renderer;

    /**
     * @var EventDispatcherInterface
     */
    private EventDispatcherInterface $dispatcher;

    public function __construct(RendererInterface $renderer, EventDispatcherInterface $dispatcher)
    {
        $this->renderer = $renderer;
        $this->dispatcher = $dispatcher;
    }

    /**
     * @param ServerRequestInterface $request
     * @return string
     */
    public function __invoke(ServerRequestInterface $request)
    {
        return $this->renderer->render('@home/index', ['param' => 'Contact']);
    }

    /**
     * @param ServerRequestInterface $request
     * @return string
     */
    public function index(ServerRequestInterface $request): string
    {
        return $this->renderer->render('@home/index', ['param' => 'Hello world']);
    }
}
