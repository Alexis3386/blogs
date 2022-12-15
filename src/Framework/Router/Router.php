<?php

namespace App\Framework\Router;

use App\Framework\Http\Request;
use App\Kernel;
use RuntimeException;

class Router
{
    private array $controllers = [];

    public function __construct(private Kernel $kernel)
    {
        if ($kernel->getParameters()->has('routes')) {
            $routes = $kernel->getParameters()->get('routes');
            foreach ($routes as $route) {
                $this->addController(new $route());
            }
        }
    }

    public function addController(ControllerInterface $controller)
    {
        $this->controllers[] = $controller;
    }

    public function findController(Request $request): ControllerInterface
    {
        foreach ($this->controllers as $controller) {
            if (preg_match($controller->getRegexPath(), $request->getPath()) > 0) {
                return $controller;
            }
        }
        // todo gérer une 404
        throw new RuntimeException('Implémenter le 404');
    }
}
