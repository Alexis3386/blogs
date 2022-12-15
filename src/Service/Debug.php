<?php

namespace App\Service;

use App\Kernel;

class Debug
{

    private \Whoops\Run $whoops;

    public function __construct(private Kernel $kernel)
    {
        $this->whoops = new \Whoops\Run();
        $this->whoops->pushHandler(new \Whoops\Handler\PrettyPageHandler);
        $this->whoops->register();
    }
}
