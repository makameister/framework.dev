<?php

namespace App\Home;

use App\Home\Controller\HomeController;
use Framework\Module;
use Framework\Renderer\RendererInterface;
use Framework\Router\Router;
use Psr\Container\ContainerInterface;

class HomeModule extends Module
{
    const CONFIG = __DIR__ . DIRECTORY_SEPARATOR . 'config.php';

    public function __construct(ContainerInterface $container)
    {
        $container->get(RendererInterface::class)->addViewPath('home', __DIR__ . '/views');

        $router = $container->get(Router::class);
        $router->get('/', [HomeController::class, 'index'], 'home.index');
        $router->get('/index', HomeController::class, 'home.index.2');
    }
}
