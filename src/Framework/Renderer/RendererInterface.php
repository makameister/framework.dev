<?php

namespace Framework\Renderer;

interface RendererInterface
{
    /**
     * @param string $namespace
     * @param string $path
     */
    public function addViewPath(string $namespace, string $path): void;

    /**
     * @param string $viewName
     * @param array<string, mixed> $params
     * @return string
     */
    public function render(string $viewName, array $params = []): string;

    /**
     * @param string $key
     * @param mixed $value
     */
    public function addGlobal(string $key, $value): void;
}
