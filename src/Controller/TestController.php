<?php
namespace App\Controller;

use App\Framework\Http\Request;
use App\Framework\Router\ControllerInterface;
use App\Kernel;

class TestController implements ControllerInterface 
{
    public function getRegexPath(): string
    {
        return '/^\/test$/';
    }

    public function execute(Kernel $kernel, Request $request): array
    {
        echo 'Test';
        return [];
    }
}