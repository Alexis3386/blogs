<?php
namespace App\Service;

class Debug
{

    public function __construct(private \Whoops\Run $whoops)
    {
        $this->registerPushHandler();
    }

    public function registerPushHandler() 
    {
        $this->whoops->pushHandler(new \Whoops\Handler\PrettyPageHandler);
        $this->whoops->register();
    }

}