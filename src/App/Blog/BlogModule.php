<?php

namespace App\Blog;

use App\Blog\Controller\BlogController;
use Framework\Module;
use Framework\Renderer\RendererInterface;
use Framework\Router\Router;
use Psr\Container\ContainerInterface;

class BlogModule extends Module
{
    const CONFIG = __DIR__ . '/config.php';

    const MIGRATIONS = __DIR__ . '/db/migrations';

    const SEEDS = __DIR__ . '/db/seeds';

    public function __construct(ContainerInterface $container)
    {
        $container->get(RendererInterface::class)->addViewPath('blog', __DIR__ . '/views');
        $container->get(Router::class)->get('/blog', [BlogController::class, 'index'], 'blog.index');
    }
}
