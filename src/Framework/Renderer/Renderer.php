<?php

namespace App\Framework\Renderer;

class Renderer implements RendererInterface
{
    const DEFAULT_NAMESPACE = '__MAIN';

    private $paths = [];

    /**
     * Variables globalement accessibles pour toutes les vues
     * @var array
     */
    private $globals = [];

    public function __construct(?string $defaultPath = null)
    {
        if ($defaultPath !== null) {
            $this->addPath($defaultPath);
        }
    }

    /**
     * Permet de rajouter un chemin pour charger les vues
     * @param string $namespace
     * @param null/string $path
     */
    public function addPath(string $namespace, ?string $path = null): void
    {
        if ($path === null) {
            $this->paths[self::DEFAULT_NAMESPACE] = $namespace;
        } else {
            $this->paths[$namespace] = $path;
        }
    }

    public function render(string $view, array $params = []): string
    {
        if ($this->hasNamespace($view)) {
            $path = $this->replaceNamespace($view) . '.php';
        } else {
            $path = $this->paths[self::DEFAULT_NAMESPACE] . DIRECTORY_SEPARATOR . $view;
        }
        ob_start();
        require($path);
        return ob_get_clean();
    }

    private function hasNamespace(string $view): bool
    {
        return $view[0] === '@';
    }

    private function getNamespace(string $view): string
    {
        return substr($view, 1, strpos($view, '/') - 1);
    }

    private function replaceNamespace(string $view): string
    {
        $namespace = $this->getNamespace($view);
        return str_replace('@' . $namespace, $this->paths[$namespace], $view);
    }

    public function addGlobal(string $key, $value): void
    {
        
    }
    
    // function render(String $template, array $parametres = []): void
    // {
    //     $loader = new \Twig\Loader\FilesystemLoader(__DIR__ . '/templates');
    //     $twig = new \Twig\Environment($loader, [
    //         'cache' => false,
    //         'debug' => true
    //     ]);

    //     global $user, $user_connecte, $categories;
    //     $defaultParam = [
    //         'user_connecte' => $user_connecte,
    //         'user' => $user,
    //         'categories' => $categories,
    //     ];
    //     if (isset($_SESSION['notification'])) {
    //         $defaultParam['notification'] = $_SESSION['notification'];
    //     }
    //     echo $twig->render($template,  array_merge($defaultParam, $parametres));
    //     unset($_SESSION['notification']);
    // }
}
