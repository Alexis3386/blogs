<?php

namespace App\Controller;

use App\Framework\Http\Request;
use App\Framework\Router\ControllerInterface;
use App\Kernel;
use App\Framework\Renderer\Renderer;


class TestController implements ControllerInterface
{
    private Renderer $renderer;

    public function __construct()
    {
        $this->renderer = new Renderer();
        $this->renderer->addPath('templates', dirname(__DIR__) . '/Framework/templates');
    }

    public function getRegexPath(): string
    {
        return '/^\/test$/';
    }

    public function execute(Kernel $kernel, Request $request): array
    {
        $this->renderer->render('@templates/test');
        return [];
    }
}
