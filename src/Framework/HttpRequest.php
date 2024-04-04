<?php

namespace App\Framework;

class HttpRequest
{
    private array $_param;
    private mixed $_url;
    private mixed $_method;
    private Route $_route;

    public function __construct()
    {
        $this->_url = $_SERVER['REQUEST_URI'];
        $this->_method = $_SERVER['REQUEST_METHOD'];
        $this->_param = array();
    }

    public function getUrl()
    {
        return $this->_url;
    }

    public function getMethod()
    {
        return $this->_method;
    }

    public function getParams(): array
    {
        return $this->_param;
    }

    public function setRoute($route): void
    {
        $this->_route = $route;
    }

    public function binParam()
    {

        switch ($this->_method) {
            case "GET":
            case "DELETE":
                if (preg_match("#" . $this->_route->getPath() . "#", $this->_url, $matches)) {
                    for ($i = 1; $i < count($matches) - 1; $i++) {
                        $this->_param[] = $matches[$i];
                    }
                };
            case "POST":
            case "PUT":
                foreach ($this->_route->getParam() as $param) {
                    $this->_param[] = $param;
                }
        }
    }
}
