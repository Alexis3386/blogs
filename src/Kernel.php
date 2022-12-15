<?php

namespace App;

use App\Framework\Http\Request;
use App\Framework\Renderer\TwigRenderer;
use App\Framework\Router\Router;
use App\Framework\Service\Parameters;
use App\Framework\Service\Service;
use App\Service\Debug;
use App\Framework\Factory\RequestFromHttpRequestFactory;

class Kernel
{
    private Router $router;
    private Service $service;
    private Debug $debug;
    private Parameters $parameters;
    private TwigRenderer $render;


    public function __construct(array $params = [])
    {
        $this->parameters = new Parameters($params);
        $this->router = new Router($this);
        $this->service = new Service($this);
        $this->debug = new Debug($this);
        $this->render = new TwigRenderer($this);
    }

    public function run(Request $request)
    {
        $controller = $this->router->findController($request);
        $controller->execute($this, $request);
    }

    public function runHttp()
    {
        $request = RequestFromHttpRequestFactory::createRequest();
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

    public function getDebug(): Debug
    {
        return $this->debug;
    }

    public function getParameters(): Parameters
    {
        return $this->parameters;
    }

    public function getRender(): TwigRenderer
    {
        return $this->render;
    }
}
