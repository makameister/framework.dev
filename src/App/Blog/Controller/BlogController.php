<?php

namespace App\Blog\Controller;

use Framework\Renderer\RendererInterface;
use Psr\EventDispatcher\EventDispatcherInterface;
use Psr\Http\Message\ServerRequestInterface;

class BlogController
{

    /**
     * @var RendererInterface
     */
    private RendererInterface $renderer;

    public function __construct(RendererInterface $renderer)
    {
        $this->renderer = $renderer;
    }

    /**
     * @param ServerRequestInterface $request
     * @param EventDispatcherInterface $dispatcher
     * @return string
     */
    public function index(ServerRequestInterface $request, EventDispatcherInterface $dispatcher): string
    {
        return $this->renderer->render('@blog/index');
    }
}
