<?php
namespace App\Framework\Router;

use App\Framework\Http\Request;
use App\Kernel;

interface ControllerInterface 
{
   public function getRegexPath(): string;
   public function execute(Kernel $kernel, Request $request): array;
}