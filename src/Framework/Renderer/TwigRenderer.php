<?php declare(strict_types = 1);

namespace Framework\Renderer;

use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;
use Twig\Loader\FilesystemLoader;

class TwigRenderer implements RendererInterface
{

    /**
     * @var FilesystemLoader
     */
    private FilesystemLoader $loader;
    /**
     * @var Environment
     */
    private Environment $environment;

    /**
     * TwigRenderer constructor.
     * @param FilesystemLoader $loader
     * @param Environment $environment
     */
    public function __construct(FilesystemLoader $loader, Environment $environment)
    {
        $this->loader = $loader;
        $this->environment = $environment;
    }

    /**
     * Ajoute un chemin vers un dossier de vues
     *
     * @param string $namespace
     * @param string $path
     * @throws LoaderError
     */
    public function addViewPath(string $namespace, string $path): void
    {
        $this->loader->addPath($path, $namespace);
    }

    /**
     * Permet de process la vue
     *
     * @param string $viewName
     * @param array<string, mixed> $params
     * @return string
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public function render(string $viewName, array $params = []): string
    {
        return $this->environment->render($viewName . '.html.twig', $params);
    }

    /**
     * Ajoute une variable accessible à toutes les vues
     *
     * @param string $key
     * @param mixed $value
     */
    public function addGlobal(string $key, $value): void
    {
        $this->environment->addGlobal($key, $value);
    }
}
