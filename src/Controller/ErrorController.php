<?php

namespace App\Controller;

class ErrorController extends BaseController
{
    public function Show($exception): void
    {
        $this->addParam("exception", $exception);
        $this->view("error.twig");
    }
}