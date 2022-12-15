<?php

namespace App\Framework\Renderer;

use App\Kernel;

class TwigRenderer implements RendererInterface
{

    private $twig;
    private $loader;

    public function __construct(private Kernel $kernel)
    {
        if (!$kernel->getParameters()->has('render.path')) {
            throw new \RuntimeException('parameters key "render.path" not found');
        }
        $path = $kernel->getParameters()->get('render.path');
        $this->loader = new \Twig\Loader\FilesystemLoader($path);
        $this->loader->addPath($path, 'templates');
        $this->twig = new \Twig\Environment($this->loader, [
            'cache' => false,
            'debug' => true
        ]);
    }

    public function render(string $view, array $params = []): string
    {
        return $this->twig->render($view . '.twig', $params);
    }

    /**
     * add global variables for all views
     */
    public function addGlobal(string $key, $value): void
    {
        $this->twig->addGlobal($key, $value);
    }
}
