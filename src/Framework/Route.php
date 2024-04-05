<?php

namespace App\Framework;

use App\Framework\Exception\ActionNotFoundException;
use App\Framework\Exception\ControllerNotFoundException;

class Route
{
    private string $path;
    private string $controller;
    private string $action;
    private string $method;
    private array $param;
    private $_manager;

    public function __construct($route)
    {
        $this->path = $route->path;
        $this->controller = $route->controller;
        $this->action = $route->action;
        $this->method = $route->method;
        $this->param = $route->param;
        $this->_manager = $route->manager;
    }

    public function getPath(): string
    {
        return $this->path;
    }

    public function getController(): string
    {
        return $this->controller;
    }

    public function getAction(): string
    {
        return $this->action;
    }

    public function getMethod(): string
    {
        return $this->method;
    }

    public function getParam(): array
    {
        return $this->param;
    }

    public function getManager()
    {
        return $this->_manager;
    }

    /**
     * @throws ActionNotFoundException
     * @throws ControllerNotFoundException
     */
    public function run($httpRequest, $config): void
    {
        $controller = null;
        $controllerName = 'App\\Controller\\' . $this->controller . "Controller";
        if (class_exists($controllerName)) {
            $controller = new $controllerName($httpRequest, $config);
            if (method_exists($controller, $this->action)) {
                $controller->{$this->action}(...$httpRequest->getParams());
            } else {
                throw new ActionNotFoundException();
            }
        } else {
            throw new ControllerNotFoundException();
        }
    }
}