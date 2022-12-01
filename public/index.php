<?php

use App\Controller\TestController;
use App\Framework\Factory\RequestFromHttpRequestFactory;
use App\Kernel;

require_once('../vendor/autoload.php');

$kernel = new Kernel();

$kernel->getRouter()->addController(new TestController());

$request = RequestFromHttpRequestFactory::createRequest();

$kernel->run($request);

