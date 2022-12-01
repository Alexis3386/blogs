<?php

namespace App;

use App\Framework\Http\Request;
use App\Framework\Router\Router;

class Kernel 
{
    private Router $router;

    public function __construct()
    {
        $this->router = new Router($this);
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
}