<?php

namespace App;

use App\Framework\Http\Request;
use App\Framework\Router\Router;
use App\Framework\Service\Service;

class Kernel
{
    private Router $router;
    private Service $service;

    public function __construct()
    {
        $this->router = new Router($this);
        $this->service = new Service($this);
    }

    public function run(Request $request) 
    {
        $controller = $this->router->findController($request);
        $controller->execute($this, $request);
    }

    public function getRouter(): Router
    {
        return $this->router;
    }

    public function getService(): Service
    {
        return $this->service;
    }
}