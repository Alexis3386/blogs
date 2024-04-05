<?php

namespace App\Framework\Exception;

use Exception;

class ActionNotFoundException extends Exception
{
    public function __construct($message = "Action not found")
    {
        parent::__construct($message, "0004");
    }
}