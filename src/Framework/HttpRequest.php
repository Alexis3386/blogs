<?php

namespace App\Framework;

use App\Framework\Exception\ActionNotFoundException;

class HttpRequest
{
    private array $_param;
    private mixed $_url;
    private mixed $_method;
    private Route $_route;

    public function __construct($url = null, $method = null)
    {
        $this->_url = (is_null($url)) ? $_SERVER['REQUEST_URI'] : $url;
        $this->_method = (is_null($method)) ? $_SERVER['REQUEST_METHOD'] : $method;
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

    public function getRoute(): Route
    {
        return $this->_route;
    }

    public function binParam(): void
    {

        switch ($this->_method) {
            case "GET":
            case "DELETE":
                foreach ($this->_route->getParam() as $param) {
                    if (isset($_GET[$param])) {
                        $this->_param[] = $_GET[$param];
                    }
                }
                break;
            case "POST":
            case "PUT":
                foreach ($this->_route->getParam() as $param) {
                    if (isset($_POST[$param])) {
                        $this->_param[] = $_POST[$param];
                    }
                }
        }
    }

    /**
     * @throws ActionNotFoundException|Exception\ControllerNotFoundException
     */
    public function run($config): void
    {
        $this->binParam();
        $this->_route->run($this, $config);
    }

    public function addParam($value): void
    {
        $this->_param[] = $value;
    }
}
