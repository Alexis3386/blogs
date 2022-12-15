<?php

require_once('../vendor/autoload.php');

use App\Kernel;

include(dirname(__DIR__) . '/config/conf.php');

$kernel = new Kernel($params);

$kernel->runHttp();
