<?php

use Framework\Event\EventDispatcher;
use Framework\Event\ListenerProvider;
use Framework\Renderer\RendererInterface;
use Framework\Renderer\TwigRendererFactory;
use Framework\Router\Router;
use Psr\Container\ContainerInterface;
use Psr\EventDispatcher\EventDispatcherInterface;

return [
    'database.host' => 'localhost',
    'database.name' => 'framework.dev',
    'database.username' => 'root',
    'database.password' => 'root',
    'database.port' => 3307,
    'view.path' => './views',
    'twig.extensions' => [],
    Router::class => \DI\create(Router::class),
    RendererInterface::class => \DI\factory(TwigRendererFactory::class),
    EventDispatcherInterface::class => \DI\create(EventDispatcher::class)->constructor(new ListenerProvider()),
    PDO::class => function (ContainerInterface $container) {
        $host = $container->get('database.host') . ':' . $container->get('database.port');
        $dsn = 'mysql:host=' . $host . ';dbname=' . $container->get('database.name');
        return new PDO(
            $dsn,
            $container->get('database.username'),
            $container->get('database.password'),
            [
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ,
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
            ]
        );
    }
];
