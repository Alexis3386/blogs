<?php

namespace App\Framework\Service;

use App\Kernel;

class Parameters
{
    public function __construct(private array $params)
    {
    }

    public function get(string $key): mixed
    {
        return $this->params[$key];
    }

    public function has(string $key): bool
    {
        return isset($this->params[$key]);
    }
}
