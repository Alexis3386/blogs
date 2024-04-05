<?php

use App\Framework\Router;
use App\Framework\HttpRequest;

require('init.php');

$configFile = file_get_contents("src/Config/config.json");
$config = json_decode($configFile);

try {
    $httpRequest = new HttpRequest();
    $router = new Router();
    $httpRequest->setRoute($router->findRoute($httpRequest));
    $httpRequest->run($config);
} catch (Exception $e) {
    $httpRequest = new HttpRequest("/error", "GET");
    $router = new Router();
    $httpRequest->setRoute($router->findRoute($httpRequest));
    $httpRequest->addParam($e);
    $httpRequest->run($config);
}
