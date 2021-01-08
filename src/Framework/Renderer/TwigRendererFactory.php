<?php declare(strict_types = 1);

namespace Framework\Renderer;

use Psr\Container\ContainerInterface;
use Twig\Environment;
use Twig\Extension\DebugExtension;
use Twig\Loader\FilesystemLoader;

class TwigRendererFactory
{

    public function __invoke(ContainerInterface $container): TwigRenderer
    {
        $viewPath = $container->get('view.path');
        $loader = new FilesystemLoader($viewPath);
        $environment = new Environment($loader, [
            'debug' => true
        ]);
        $environment->addExtension(new DebugExtension());
        if ($container->has('twig.extensions')) {
            foreach ($container->get('twig.extensions') as $extension) {
                $environment->addExtension($extension);
            }
        }
        return new TwigRenderer($loader, $environment);
    }
}
