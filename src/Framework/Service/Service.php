<?php

namespace App\Framework\Service;
use App\Kernel;

class Service
{
    private array $services = [];

    public function __construct(private Kernel $kernel)
    {

    }

    public function addService($service)
    {
        $this->services[] = $service;
    }
}