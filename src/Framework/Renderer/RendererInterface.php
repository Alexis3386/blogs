<?php

namespace App\Framework\Renderer;

interface RendererInterface
{


    public function render(string $view, array $params = []): string;

    /**
     * add global variables for all views
     */
    public function addGlobal(string $key, $value): void;
    
}