<?php

namespace App\Framework\Exception;

use Exception;

class NoRouteFoundException extends Exception
{

    private $_httpRequest;

    public function __construct($message = "No route has been found") {
        parent::__construct($message, "0002");
    }

    public function getMoreDetail(): string
    {
        return "Route '" . $this->_httpRequest->getUrl() . "' has not been found with method '" . $this->_httpRequest->getMethod() . "'";
    }
}