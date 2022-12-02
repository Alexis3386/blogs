<?php

use App\Controller\TestController;
use App\Framework\Factory\RequestFromHttpRequestFactory;
use App\Kernel;
use App\Service\Debug;

require_once('../vendor/autoload.php');

new Debug(new \Whoops\Run);

$kernel = new Kernel();

$kernel->getRouter()->addController(new TestController());

$request = RequestFromHttpRequestFactory::createRequest();

$kernel->run($request);
