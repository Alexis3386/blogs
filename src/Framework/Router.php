<?php

namespace App\Framework;

use App\Framework\Exception\MultipleRouteFoundException;
use App\Framework\Exception\NoRouteFoundException;

class Router
{
    private mixed $listRoute;

    public function __construct()
    {
        $stringRoute = file_get_contents('src/Config/route.json');
        $this->listRoute = json_decode($stringRoute);
    }

    /**
     * @throws MultipleRouteFoundException
     * @throws NoRouteFoundException
     */
    public function findRoute($httpRequest): Route
    {
        $routeFound = array_filter($this->listRoute, function ($route) use ($httpRequest) {
            return preg_match("#^" . $route->path . "$#", $httpRequest->getUrl()) && $route->method == $httpRequest->getMethod();
        });
        $numberRoute = count($routeFound);
        if ($numberRoute > 1) {
            throw new MultipleRouteFoundException();
        } else if ($numberRoute == 0) {
            throw new NoRouteFoundException();
        } else {
            return new Route(array_shift($routeFound));
        }
    }
}
