<?php

namespace App\Framework\Factory;

use App\Framework\Http\Request;

class RequestFromHttpRequestFactory
{

    public static function createRequest() : Request
    {
        return new Request(
            parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH),
            $_GET,
            $_POST,
            $_COOKIE
        );
    }
}